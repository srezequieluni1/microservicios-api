<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Client - Laravel 12</title>

    <style>
        /* Reset y estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            min-height: calc(100vh - 200px);
        }

        /* Panel de solicitud */
        .request-panel {
            padding: 30px;
            border-right: 1px solid #e1e5e9;
            background: #f8f9fa;
        }

        .panel-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .panel-title::before {
            content: "📤";
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            transform: translateY(-1px);
        }

        .form-control:hover {
            border-color: #bdc3c7;
        }

        .method-url-group {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 15px;
            align-items: end;
        }

        .method-select {
            padding: 12px 10px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            background: white;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .method-select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .method-select option {
            padding: 10px;
            font-weight: 600;
        }

        .method-select option[value="GET"] { color: #27ae60; }
        .method-select option[value="POST"] { color: #3498db; }
        .method-select option[value="PUT"] { color: #f39c12; }
        .method-select option[value="DELETE"] { color: #e74c3c; }
        .method-select option[value="PATCH"] { color: #9b59b6; }

        .textarea-control {
            min-height: 150px;
            resize: vertical;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .execute-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .execute-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(39, 174, 96, 0.3);
        }

        .execute-button:active {
            transform: translateY(0);
        }

        .execute-button:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .loading {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Panel de respuesta */
        .response-panel {
            padding: 30px;
            background: white;
        }

        .response-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .response-title::before {
            content: "📥";
            font-size: 1.2rem;
        }

        .response-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            text-align: center;
            border: 2px solid;
        }

        .status-success {
            background: #d5f4e6;
            color: #27ae60;
            border-color: #27ae60;
        }

        .status-error {
            background: #ffeaea;
            color: #e74c3c;
            border-color: #e74c3c;
        }

        .status-warning {
            background: #fff3cd;
            color: #f39c12;
            border-color: #f39c12;
        }

        .response-time {
            padding: 8px 15px;
            background: #f8f9fa;
            border: 2px solid #e1e5e9;
            border-radius: 20px;
            font-weight: 600;
            text-align: center;
            color: #34495e;
        }

        .headers-section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .headers-content {
            background: #f8f9fa;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            max-height: 120px;
            overflow-y: auto;
            white-space: pre-wrap;
        }

        .response-body {
            background: #2c3e50;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .response-body-header {
            background: #34495e;
            padding: 10px 15px;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .copy-button {
            background: transparent;
            border: 1px solid #7f8c8d;
            color: #ecf0f1;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .copy-button:hover {
            background: #7f8c8d;
            color: white;
        }

        .json-content {
            padding: 20px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
            color: #ecf0f1;
            max-height: 400px;
            overflow-y: auto;
            background: #2c3e50;
        }

        /* Syntax highlighting para JSON */
        .json-key { color: #3498db; }
        .json-string { color: #2ecc71; }
        .json-number { color: #f39c12; }
        .json-boolean { color: #e74c3c; }
        .json-null { color: #95a5a6; }

        .empty-response {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .empty-response-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }

            .request-panel {
                border-right: none;
                border-bottom: 1px solid #e1e5e9;
            }

            .method-url-group {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .response-info {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 2rem;
            }

            .container {
                margin: 10px;
                border-radius: 8px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .request-panel,
            .response-panel {
                padding: 20px;
            }

            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 1.8rem;
            }
        }

        /* Animaciones */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Error states */
        .form-error {
            border-color: #e74c3c !important;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
        }

        .show-error {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>API Client</h1>
            <p>Herramienta para probar endpoints de API - Laravel 12</p>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <!-- Panel de solicitud -->
            <div class="request-panel">
                <h2 class="panel-title">Request</h2>

                <form id="apiForm">
                    <!-- Método HTTP y URL -->
                    <div class="form-group">
                        <label class="form-label">Endpoint</label>
                        <div class="method-url-group">
                            <select id="httpMethod" class="method-select" required>
                                <option value="GET">GET</option>
                                <option value="POST">POST</option>
                                <option value="PUT">PUT</option>
                                <option value="DELETE">DELETE</option>
                                <option value="PATCH">PATCH</option>
                            </select>
                            <div>
                                <input
                                    type="url"
                                    id="apiUrl"
                                    class="form-control"
                                    placeholder="https://api.ejemplo.com/endpoint"
                                    required
                                >
                                <div class="error-message" id="urlError">Por favor ingresa una URL válida</div>
                            </div>
                        </div>
                    </div>

                    <!-- Headers personalizados -->
                    <div class="form-group">
                        <label class="form-label" for="customHeaders">Headers (JSON)</label>
                        <textarea
                            id="customHeaders"
                            class="form-control textarea-control"
                            placeholder='{"Content-Type": "application/json", "Authorization": "Bearer token"}'
                            style="min-height: 100px;"
                        ></textarea>
                        <div class="error-message" id="headersError">Formato JSON inválido en headers</div>
                    </div>

                    <!-- Cuerpo de la petición -->
                    <div class="form-group">
                        <label class="form-label" for="requestBody">Request Body (JSON)</label>
                        <textarea
                            id="requestBody"
                            class="form-control textarea-control"
                            placeholder='{"key": "value", "data": {"nested": "object"}}'
                        ></textarea>
                        <div class="error-message" id="bodyError">Formato JSON inválido en el body</div>
                    </div>

                    <!-- Botón de ejecución -->
                    <button type="submit" id="executeButton" class="execute-button">
                        <span class="button-text">Ejecutar Request</span>
                        <div class="loading">
                            <div class="spinner"></div>
                        </div>
                    </button>
                </form>
            </div>

            <!-- Panel de respuesta -->
            <div class="response-panel">
                <h2 class="response-title">Response</h2>

                <div id="responseContainer" class="empty-response">
                    <div class="empty-response-icon">🚀</div>
                    <p>Ejecuta una petición para ver la respuesta aquí</p>
                </div>

                <!-- Contenido de respuesta (oculto inicialmente) -->
                <div id="responseContent" style="display: none;">
                    <!-- Información de respuesta -->
                    <div class="response-info">
                        <div id="statusBadge" class="status-badge"></div>
                        <div id="responseTime" class="response-time"></div>
                    </div>

                    <!-- Headers de respuesta -->
                    <div class="headers-section">
                        <div class="section-title">Response Headers</div>
                        <div id="responseHeaders" class="headers-content"></div>
                    </div>

                    <!-- Cuerpo de respuesta -->
                    <div class="response-body">
                        <div class="response-body-header">
                            <span>Response Body</span>
                            <button id="copyButton" class="copy-button">Copy JSON</button>
                        </div>
                        <div id="responseBody" class="json-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let currentResponse = null;

        // Referencias a elementos del DOM
        const apiForm = document.getElementById('apiForm');
        const httpMethodSelect = document.getElementById('httpMethod');
        const apiUrlInput = document.getElementById('apiUrl');
        const customHeadersInput = document.getElementById('customHeaders');
        const requestBodyInput = document.getElementById('requestBody');
        const executeButton = document.getElementById('executeButton');
        const buttonText = document.querySelector('.button-text');
        const loadingSpinner = document.querySelector('.loading');

        const responseContainer = document.getElementById('responseContainer');
        const responseContent = document.getElementById('responseContent');
        const statusBadge = document.getElementById('statusBadge');
        const responseTime = document.getElementById('responseTime');
        const responseHeaders = document.getElementById('responseHeaders');
        const responseBody = document.getElementById('responseBody');
        const copyButton = document.getElementById('copyButton');

        // Event listeners
        apiForm.addEventListener('submit', handleFormSubmit);
        copyButton.addEventListener('click', copyResponseToClipboard);

        // Validación en tiempo real
        apiUrlInput.addEventListener('blur', validateUrl);
        customHeadersInput.addEventListener('blur', () => validateJSON(customHeadersInput, 'headersError'));
        requestBodyInput.addEventListener('blur', () => validateJSON(requestBodyInput, 'bodyError'));

        /**
         * Maneja el envío del formulario
         */
        async function handleFormSubmit(event) {
            event.preventDefault();

            // Validar formulario antes de enviar
            if (!validateForm()) {
                return;
            }

            // Mostrar estado de carga
            setLoadingState(true);

            try {
                const startTime = performance.now();

                // Preparar la petición
                const requestOptions = await prepareRequest();

                // Realizar la petición HTTP
                const response = await fetch(apiUrlInput.value.trim(), requestOptions);

                const endTime = performance.now();
                const duration = Math.round(endTime - startTime);

                // Procesar la respuesta
                await handleResponse(response, duration);

            } catch (error) {
                console.error('Error en la petición:', error);
                handleError(error);
            } finally {
                setLoadingState(false);
            }
        }

        /**
         * Valida todo el formulario
         */
        function validateForm() {
            let isValid = true;

            // Validar URL
            if (!validateUrl()) {
                isValid = false;
            }

            // Validar headers JSON
            if (!validateJSON(customHeadersInput, 'headersError')) {
                isValid = false;
            }

            // Validar body JSON
            if (!validateJSON(requestBodyInput, 'bodyError')) {
                isValid = false;
            }

            return isValid;
        }

        /**
         * Valida la URL ingresada
         */
        function validateUrl() {
            const url = apiUrlInput.value.trim();
            const urlError = document.getElementById('urlError');

            if (!url) {
                showFieldError(apiUrlInput, urlError, 'La URL es requerida');
                return false;
            }

            try {
                new URL(url);
                hideFieldError(apiUrlInput, urlError);
                return true;
            } catch {
                showFieldError(apiUrlInput, urlError, 'Por favor ingresa una URL válida');
                return false;
            }
        }

        /**
         * Valida formato JSON en un campo
         */
        function validateJSON(input, errorId) {
            const value = input.value.trim();
            const errorElement = document.getElementById(errorId);

            if (!value) {
                hideFieldError(input, errorElement);
                return true;
            }

            try {
                JSON.parse(value);
                hideFieldError(input, errorElement);
                return true;
            } catch {
                showFieldError(input, errorElement, 'Formato JSON inválido');
                return false;
            }
        }

        /**
         * Muestra error en un campo
         */
        function showFieldError(input, errorElement, message) {
            input.classList.add('form-error');
            errorElement.textContent = message;
            errorElement.classList.add('show-error');
        }

        /**
         * Oculta error en un campo
         */
        function hideFieldError(input, errorElement) {
            input.classList.remove('form-error');
            errorElement.classList.remove('show-error');
        }

        /**
         * Prepara las opciones de la petición HTTP
         */
        async function prepareRequest() {
            const method = httpMethodSelect.value;
            const headers = {};

            // Parsear headers personalizados
            const customHeaders = customHeadersInput.value.trim();
            if (customHeaders) {
                try {
                    Object.assign(headers, JSON.parse(customHeaders));
                } catch (error) {
                    console.warn('Error parseando headers personalizados:', error);
                }
            }

            // Configurar opciones básicas
            const options = {
                method: method,
                headers: headers,
                mode: 'cors',
                credentials: 'same-origin'
            };

            // Agregar body para métodos que lo permiten
            if (['POST', 'PUT', 'PATCH'].includes(method)) {
                const body = requestBodyInput.value.trim();
                if (body) {
                    options.body = body;
                    // Asegurar Content-Type si no está definido
                    if (!headers['Content-Type'] && !headers['content-type']) {
                        headers['Content-Type'] = 'application/json';
                    }
                }
            }

            return options;
        }

        /**
         * Maneja la respuesta HTTP
         */
        async function handleResponse(response, duration) {
            currentResponse = response;

            // Mostrar información básica
            displayResponseInfo(response, duration);

            // Mostrar headers
            displayResponseHeaders(response);

            // Procesar y mostrar el body
            await displayResponseBody(response);

            // Mostrar el panel de respuesta
            showResponsePanel();
        }

        /**
         * Muestra información básica de la respuesta
         */
        function displayResponseInfo(response, duration) {
            // Status badge
            const status = response.status;
            statusBadge.textContent = `${status} ${response.statusText}`;
            statusBadge.className = 'status-badge ' + getStatusClass(status);

            // Tiempo de respuesta
            responseTime.textContent = `${duration}ms`;
        }

        /**
         * Determina la clase CSS según el código de estado
         */
        function getStatusClass(status) {
            if (status >= 200 && status < 300) return 'status-success';
            if (status >= 400 && status < 500) return 'status-error';
            if (status >= 500) return 'status-error';
            return 'status-warning';
        }

        /**
         * Muestra los headers de respuesta
         */
        function displayResponseHeaders(response) {
            const headersObj = {};
            for (const [key, value] of response.headers.entries()) {
                headersObj[key] = value;
            }

            responseHeaders.textContent = JSON.stringify(headersObj, null, 2);
        }

        /**
         * Procesa y muestra el body de la respuesta
         */
        async function displayResponseBody(response) {
            try {
                const contentType = response.headers.get('content-type') || '';

                if (contentType.includes('application/json')) {
                    const jsonData = await response.json();
                    const formattedJson = JSON.stringify(jsonData, null, 2);
                    responseBody.innerHTML = syntaxHighlightJSON(formattedJson);
                } else {
                    const textData = await response.text();
                    responseBody.textContent = textData || '(Respuesta vacía)';
                }
            } catch (error) {
                console.error('Error procesando respuesta:', error);
                responseBody.textContent = 'Error procesando la respuesta: ' + error.message;
            }
        }

        /**
         * Aplica syntax highlighting a JSON
         */
        function syntaxHighlightJSON(json) {
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                let cls = 'json-number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'json-key';
                    } else {
                        cls = 'json-string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'json-boolean';
                } else if (/null/.test(match)) {
                    cls = 'json-null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }

        /**
         * Muestra el panel de respuesta con animación
         */
        function showResponsePanel() {
            responseContainer.style.display = 'none';
            responseContent.style.display = 'block';
            responseContent.classList.add('fade-in');
        }

        /**
         * Maneja errores de red o de petición
         */
        function handleError(error) {
            console.error('Error en la petición:', error);

            // Mostrar error en el panel de respuesta
            responseContainer.style.display = 'none';
            responseContent.style.display = 'block';

            statusBadge.textContent = 'Error de Red';
            statusBadge.className = 'status-badge status-error';

            responseTime.textContent = 'N/A';
            responseHeaders.textContent = 'No disponible';
            responseBody.innerHTML = `<span style="color: #e74c3c;">Error: ${error.message}</span>`;

            responseContent.classList.add('fade-in');
        }

        /**
         * Controla el estado de carga del botón
         */
        function setLoadingState(isLoading) {
            if (isLoading) {
                executeButton.disabled = true;
                buttonText.style.display = 'none';
                loadingSpinner.style.display = 'block';
            } else {
                executeButton.disabled = false;
                buttonText.style.display = 'block';
                loadingSpinner.style.display = 'none';
            }
        }

        /**
         * Copia la respuesta JSON al portapapeles
         */
        async function copyResponseToClipboard() {
            try {
                const text = responseBody.textContent || responseBody.innerText;
                await navigator.clipboard.writeText(text);

                // Feedback visual
                const originalText = copyButton.textContent;
                copyButton.textContent = 'Copiado!';
                copyButton.style.background = '#27ae60';

                setTimeout(() => {
                    copyButton.textContent = originalText;
                    copyButton.style.background = 'transparent';
                }, 2000);

            } catch (error) {
                console.error('Error copiando al portapapeles:', error);
                alert('Error al copiar al portapapeles');
            }
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar valores por defecto
            apiUrlInput.value = window.location.origin + '/api/';
            customHeadersInput.value = JSON.stringify({
                "Content-Type": "application/json",
                "Accept": "application/json"
            }, null, 2);

            console.log('API Client inicializado correctamente');
        });
    </script>
</body>
</html>
