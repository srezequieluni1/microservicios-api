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
            <div class="header-content">
                <div class="header-title">
                    <h1>API Client</h1>
                    <span class="header-subtitle">Laravel 12</span>
                </div>
                <div class="header-links">
                    <a href="/docs/api-client" class="header-link">📚 Docs</a>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <!-- Panel de solicitud -->
            <div class="request-panel">
                <h2 class="panel-title">Request</h2>

                <form id="apiForm">
                    <!-- Nombre de la consulta -->
                    <div class="form-group">
                        <label class="form-label" for="queryName">Nombre de la consulta</label>
                        <input
                            type="text"
                            id="queryName"
                            class="form-control"
                            placeholder="Mi consulta API"
                            maxlength="100"
                        >
                        <div class="form-hint">Dale un nombre identificativo a esta consulta para guardarla en el historial</div>
                    </div>

                    <!-- Historial de consultas -->
                    <div class="form-group">
                        <label class="form-label">Historial de consultas</label>
                        <div class="history-group">
                            <button type="button" id="newQueryButton" class="new-query-button" title="Nueva consulta">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                            </button>
                            <select id="queryHistory" class="form-control">
                                <option value="">Seleccionar consulta previa...</option>
                            </select>
                            <button type="button" id="deleteHistoryButton" class="delete-history-button" title="Eliminar consulta seleccionada">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14ZM10 11v6M14 11v6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

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
    <script src="{{ asset('js/api-client-history.js') }}"></script>
</body>
</html>
