# Ejemplo de Cliente JavaScript para la API

## Función Auxiliar para Manejar Respuestas

```javascript
/**
 * Maneja las respuestas de la API con la estructura estandarizada
 * @param {Response} response - Respuesta fetch de la API
 * @returns {Promise<Object>} - Datos de la respuesta o error
 */
async function handleApiResponse(response) {
    const data = await response.json();
    
    if (!data.success) {
        // Manejar errores
        const error = new Error(data.message);
        error.status = response.status;
        error.errors = data.errors;
        throw error;
    }
    
    return data;
}

/**
 * Cliente para realizar peticiones a la API
 */
class ApiClient {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
        this.token = localStorage.getItem('auth_token');
    }

    // Configurar headers con autenticación
    getHeaders() {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        return headers;
    }

    // Verificar estado de la API
    async ping() {
        try {
            const response = await fetch(`${this.baseUrl}/api/ping`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            return await handleApiResponse(response);
        } catch (error) {
            console.error('Error en ping:', error.message);
            throw error;
        }
    }

    // Registrar usuario
    async register(userData) {
        try {
            const response = await fetch(`${this.baseUrl}/api/register`, {
                method: 'POST',
                headers: this.getHeaders(),
                body: JSON.stringify(userData)
            });

            const result = await handleApiResponse(response);
            
            // Guardar token automáticamente
            if (result.data.token) {
                this.token = result.data.token;
                localStorage.setItem('auth_token', this.token);
            }

            return result;
        } catch (error) {
            console.error('Error en registro:', error.message);
            if (error.errors) {
                console.error('Errores de validación:', error.errors);
            }
            throw error;
        }
    }

    // Iniciar sesión
    async login(credentials) {
        try {
            const response = await fetch(`${this.baseUrl}/api/login`, {
                method: 'POST',
                headers: this.getHeaders(),
                body: JSON.stringify(credentials)
            });

            const result = await handleApiResponse(response);
            
            // Guardar token automáticamente
            if (result.data.token) {
                this.token = result.data.token;
                localStorage.setItem('auth_token', this.token);
            }

            return result;
        } catch (error) {
            console.error('Error en login:', error.message);
            throw error;
        }
    }

    // Obtener datos del usuario
    async getUser() {
        try {
            const response = await fetch(`${this.baseUrl}/api/user`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            return await handleApiResponse(response);
        } catch (error) {
            console.error('Error obteniendo usuario:', error.message);
            throw error;
        }
    }

    // Cerrar sesión
    async logout() {
        try {
            const response = await fetch(`${this.baseUrl}/api/logout`, {
                method: 'POST',
                headers: this.getHeaders()
            });

            const result = await handleApiResponse(response);
            
            // Limpiar token
            this.token = null;
            localStorage.removeItem('auth_token');

            return result;
        } catch (error) {
            console.error('Error en logout:', error.message);
            throw error;
        }
    }

    // Reenviar email de verificación
    async resendVerificationEmail() {
        try {
            const response = await fetch(`${this.baseUrl}/api/email/verification-notification`, {
                method: 'POST',
                headers: this.getHeaders()
            });

            return await handleApiResponse(response);
        } catch (error) {
            console.error('Error reenviando email:', error.message);
            throw error;
        }
    }
}

// Ejemplo de uso
const api = new ApiClient('http://localhost:8000');

// Verificar estado de la API
api.ping()
.then(result => {
    console.log('API funcionando:', result.message);
    console.log('Estado:', result.data.status);
})
.catch(error => {
    console.error('API no disponible:', error.message);
});

// Registro
api.register({
    name: 'Juan Pérez',
    email: 'juan@example.com',
    password: 'secret123',
    password_confirmation: 'secret123'
})
.then(result => {
    console.log('Registro exitoso:', result.message);
    console.log('Usuario:', result.data.user);
    console.log('Token:', result.data.token);
})
.catch(error => {
    console.error('Error en registro:', error.message);
    if (error.errors) {
        // Mostrar errores de validación específicos
        Object.keys(error.errors).forEach(field => {
            console.error(`${field}: ${error.errors[field].join(', ')}`);
        });
    }
});

// Login
api.login({
    email: 'juan@example.com',
    password: 'secret123'
})
.then(result => {
    console.log('Login exitoso:', result.message);
    console.log('Usuario logueado:', result.data.user.name);
})
.catch(error => {
    console.error('Credenciales incorrectas:', error.message);
});
```

## Manejo de Errores con React/Vue

### React Hook
```javascript
import { useState, useCallback } from 'react';

export function useApi() {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const callApi = useCallback(async (apiFunction) => {
        setLoading(true);
        setError(null);

        try {
            const result = await apiFunction();
            return result;
        } catch (error) {
            setError({
                message: error.message,
                errors: error.errors || null,
                status: error.status
            });
            throw error;
        } finally {
            setLoading(false);
        }
    }, []);

    return { callApi, loading, error };
}

// Uso en componente
function LoginForm() {
    const { callApi, loading, error } = useApi();
    const api = new ApiClient('http://localhost:8000');

    const handleLogin = async (credentials) => {
        try {
            const result = await callApi(() => api.login(credentials));
            console.log('Login exitoso:', result.data.user);
        } catch (error) {
            // El error ya está en el estado
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            {error && (
                <div className="error">
                    <p>{error.message}</p>
                    {error.errors && Object.entries(error.errors).map(([field, messages]) => (
                        <ul key={field}>
                            {messages.map((message, index) => (
                                <li key={index}>{field}: {message}</li>
                            ))}
                        </ul>
                    ))}
                </div>
            )}
            {/* Campos del formulario */}
        </form>
    );
}
```

### Vue Composable
```javascript
import { ref } from 'vue';

export function useApi() {
    const loading = ref(false);
    const error = ref(null);

    const callApi = async (apiFunction) => {
        loading.value = true;
        error.value = null;

        try {
            const result = await apiFunction();
            return result;
        } catch (err) {
            error.value = {
                message: err.message,
                errors: err.errors || null,
                status: err.status
            };
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return { callApi, loading, error };
}
```
