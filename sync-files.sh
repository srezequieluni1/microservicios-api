#!/bin/bash

# Script para actualizar archivos comunes desde el branch main
# Uso: ./sync-common-files.sh [branch-origen] [branch-destino]

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para mostrar ayuda
show_help() {
    echo -e "${BLUE}Uso: $0 [branch-origen] [branch-destino]${NC}"
    echo ""
    echo "Descripción:"
    echo "  Actualiza archivos comunes que han cambiado desde el branch origen al destino"
    echo ""
    echo "Parámetros:"
    echo "  branch-origen   Branch desde el cual obtener los cambios (default: main)"
    echo "  branch-destino  Branch al cual aplicar los cambios (default: branch actual)"
    echo ""
    echo "Ejemplos:"
    echo "  $0                    # Actualiza desde main al branch actual"
    echo "  $0 main develop      # Actualiza desde main a develop"
    echo "  $0 --help           # Muestra esta ayuda"
}

# Verificar si se solicita ayuda
if [[ "$1" == "--help" || "$1" == "-h" ]]; then
    show_help
    exit 0
fi

# Configurar branches
SOURCE_BRANCH=${1:-"main"}
TARGET_BRANCH=${2:-$(git branch --show-current)}

echo -e "${BLUE}=== Script de Sincronización de Archivos Comunes ===${NC}"
echo -e "${YELLOW}Branch origen: $SOURCE_BRANCH${NC}"
echo -e "${YELLOW}Branch destino: $TARGET_BRANCH${NC}"
echo ""

# Verificar que estamos en un repositorio git
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    echo -e "${RED}Error: No estás en un repositorio Git${NC}"
    exit 1
fi

# Verificar que el branch origen existe
if ! git show-ref --verify --quiet refs/heads/$SOURCE_BRANCH && ! git show-ref --verify --quiet refs/remotes/origin/$SOURCE_BRANCH; then
    echo -e "${RED}Error: El branch '$SOURCE_BRANCH' no existe${NC}"
    exit 1
fi

# Cambiar al branch destino si no estamos en él
CURRENT_BRANCH=$(git branch --show-current)
if [[ "$CURRENT_BRANCH" != "$TARGET_BRANCH" ]]; then
    echo -e "${YELLOW}Cambiando al branch: $TARGET_BRANCH${NC}"
    git checkout $TARGET_BRANCH
    if [[ $? -ne 0 ]]; then
        echo -e "${RED}Error: No se pudo cambiar al branch '$TARGET_BRANCH'${NC}"
        exit 1
    fi
fi

# Fetch para asegurar que tenemos los últimos cambios
echo -e "${YELLOW}Obteniendo últimos cambios...${NC}"
git fetch origin $SOURCE_BRANCH

# Determinar la referencia del branch origen
if git show-ref --verify --quiet refs/heads/$SOURCE_BRANCH; then
    SOURCE_REF=$SOURCE_BRANCH
else
    SOURCE_REF=origin/$SOURCE_BRANCH
fi

# Obtener lista de archivos que han cambiado
echo -e "${YELLOW}Analizando diferencias entre branches...${NC}"
CHANGED_FILES=$(git diff --name-only HEAD $SOURCE_REF)

if [[ -z "$CHANGED_FILES" ]]; then
    echo -e "${GREEN}✓ No hay diferencias entre los branches${NC}"
    exit 0
fi

# Filtrar solo archivos que existen en ambos branches (archivos comunes)
COMMON_FILES=""
while IFS= read -r file; do
    if [[ -f "$file" ]]; then
        COMMON_FILES="$COMMON_FILES$file\n"
    fi
done <<< "$CHANGED_FILES"

# Remover el último \n
COMMON_FILES=$(echo -e "$COMMON_FILES" | sed '/^$/d')

if [[ -z "$COMMON_FILES" ]]; then
    echo -e "${YELLOW}No hay archivos comunes que actualizar${NC}"
    echo -e "${BLUE}Archivos que han cambiado pero no existen en el branch actual:${NC}"
    echo "$CHANGED_FILES"
    exit 0
fi

# Mostrar archivos que se van a actualizar
echo -e "${BLUE}Archivos comunes que se actualizarán:${NC}"
echo -e "$COMMON_FILES"
echo ""

# Pedir confirmación
read -p "¿Deseas continuar con la actualización? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}Operación cancelada${NC}"
    exit 0
fi

# Crear backup de los archivos actuales
BACKUP_DIR=".backup-$(date +%Y%m%d_%H%M%S)"
echo -e "${YELLOW}Creando backup en: $BACKUP_DIR${NC}"
mkdir -p "$BACKUP_DIR"

while IFS= read -r file; do
    if [[ -n "$file" ]]; then
        # Crear directorio padre en backup si no existe
        backup_file_dir=$(dirname "$BACKUP_DIR/$file")
        mkdir -p "$backup_file_dir"
        # Copiar archivo al backup
        cp "$file" "$BACKUP_DIR/$file"
    fi
done <<< "$COMMON_FILES"

# Actualizar archivos desde el branch origen
echo -e "${YELLOW}Actualizando archivos...${NC}"
ERROR_COUNT=0

while IFS= read -r file; do
    if [[ -n "$file" ]]; then
        echo -e "  Actualizando: ${GREEN}$file${NC}"
        git checkout $SOURCE_REF -- "$file"
        if [[ $? -ne 0 ]]; then
            echo -e "  ${RED}Error actualizando: $file${NC}"
            ((ERROR_COUNT++))
        fi
    fi
done <<< "$COMMON_FILES"

# Verificar si hubo errores
if [[ $ERROR_COUNT -gt 0 ]]; then
    echo -e "${RED}Se encontraron $ERROR_COUNT errores durante la actualización${NC}"
    echo -e "${YELLOW}Puedes restaurar los archivos desde: $BACKUP_DIR${NC}"
    exit 1
fi

# Agregar archivos al staging area
echo -e "${YELLOW}Agregando archivos al staging area...${NC}"
while IFS= read -r file; do
    if [[ -n "$file" ]]; then
        git add "$file"
    fi
done <<< "$COMMON_FILES"

# Mostrar estado final
echo ""
echo -e "${GREEN}✓ Actualización completada exitosamente${NC}"
echo -e "${BLUE}Estado del repositorio:${NC}"
git status --short

# Preguntar si se desea hacer commit
echo ""
read -p "¿Deseas hacer commit de los cambios? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    COMMIT_MSG="Update common files from $SOURCE_BRANCH

Files updated:
$(echo -e "$COMMON_FILES" | sed 's/^/- /')"
    
    git commit -m "$COMMIT_MSG"
    echo -e "${GREEN}✓ Commit realizado${NC}"
else
    echo -e "${YELLOW}Los cambios están en staging area. Puedes hacer commit manualmente.${NC}"
fi

# Preguntar si se desea eliminar el backup
echo ""
read -p "¿Deseas eliminar el backup creado? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    rm -rf "$BACKUP_DIR"
    echo -e "${GREEN}✓ Backup eliminado${NC}"
else
    echo -e "${BLUE}Backup conservado en: $BACKUP_DIR${NC}"
fi

echo ""
echo -e "${GREEN}¡Proceso completado!${NC}"
