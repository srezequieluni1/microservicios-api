<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Client - Laravel 12</title>

    <!-- Estilos CSS -->
    <link rel="stylesheet" href="{{ asset('css/api-client-variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/api-client.css') }}">
    <link rel="stylesheet" href="{{ asset('css/api-client-responsive.css') }}">
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

    <!-- JavaScript -->
    <script src="{{ asset('js/api-client.js') }}"></script>
</body>
</html>
