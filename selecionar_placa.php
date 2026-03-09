<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Placa Base - Configurador PC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: white;
            border-radius: 20px 20px 0 0;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .header h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5em;
        }

        .header p {
            color: #666;
        }

        .main-content {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        /* Panel de filtros */
        .filters-panel {
            background: white;
            border-radius: 20px;
            padding: 25px;
            height: fit-content;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: sticky;
            top: 20px;
        }

        .filters-panel h3 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .filter-group {
            margin-bottom: 20px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
            font-size: 0.95em;
        }

        .filter-group select, .filter-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.95em;
            transition: all 0.3s ease;
        }

        .filter-group select:focus, .filter-group input:focus {
            border-color: #667eea;
            outline: none;
        }

        .filter-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-filtrar {
            width: 100%;
            padding: 15px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        .btn-filtrar:hover {
            background: #764ba2;
        }

        .btn-limpiar {
            width: 100%;
            padding: 12px;
            background: #f0f0f0;
            color: #666;
            border: none;
            border-radius: 10px;
            font-size: 0.95em;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        .btn-limpiar:hover {
            background: #e0e0e0;
        }

        /* Panel de resultados */
        .results-panel {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .search-box {
            margin-bottom: 25px;
        }

        .search-box input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 1.1em;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .results-count {
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
        }

        .grid-placas {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .placa-card {
            border: 2px solid #f0f0f0;
            border-radius: 15px;
            padding: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .placa-card:hover {
            transform: translateY(-5px);
            border-color: #667eea;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        }

        .placa-card.seleccionada {
            border-color: #28a745;
            background: #f0fff4;
        }

        .placa-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .placa-marca {
            background: #f0f0f0;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            color: #555;
        }

        .gama-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            color: white;
        }

        .gama-alta { background: #ff4757; }
        .gama-media { background: #ffa502; }
        .gama-baja { background: #26de81; }

        .placa-nombre {
            font-size: 1.2em;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .compatibilidad-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .compat-item {
            text-align: center;
        }

        .compat-label {
            font-size: 0.75em;
            color: #666;
            margin-bottom: 3px;
        }

        .compat-value {
            font-weight: 600;
            color: #333;
            font-size: 0.9em;
        }

        .especificaciones {
            font-size: 0.9em;
            color: #666;
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 10px;
            line-height: 1.5;
        }

        .placa-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .precio {
            font-size: 1.4em;
            font-weight: 700;
            color: #28a745;
        }

        .btn-seleccionar {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-seleccionar:hover {
            background: #764ba2;
        }

        .badge-wifi {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.75em;
            font-weight: 600;
        }

        .loading {
            text-align: center;
            padding: 50px;
            color: #666;
            display: none;
        }

        .loading::after {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .no-results {
            text-align: center;
            padding: 60px;
            color: #999;
        }

        .no-results i {
            font-size: 4em;
            margin-bottom: 20px;
            display: block;
        }

        .volver-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        .volver-btn:hover {
            background: #764ba2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 SELECCIONAR PLACA BASE</h1>
            <p>Busca y filtra placas base por compatibilidad con tu CPU, RAM y otros componentes</p>
        </div>

        <div class="main-content">
            <!-- Panel de filtros -->
            <div class="filters-panel">
                <h3>🔍 FILTROS</h3>
                
                <div class="filter-group">
                    <label>Socket</label>
                    <select id="filtroSocket">
                        <option value="">Todos los sockets</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Chipset</label>
                    <select id="filtroChipset">
                        <option value="">Todos los chipsets</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Formato</label>
                    <select id="filtroFormato">
                        <option value="">Todos los formatos</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Tipo de RAM</label>
                    <select id="filtroTipoRAM">
                        <option value="">Todos los tipos</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Marca</label>
                    <select id="filtroMarca">
                        <option value="">Todas las marcas</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Gama</label>
                    <select id="filtroGama">
                        <option value="">Todas las gamas</option>
                        <option value="Alta">🔴 Alta</option>
                        <option value="Media">🟡 Media</option>
                        <option value="Baja">🟢 Baja</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Wi-Fi</label>
                    <select id="filtroWifi">
                        <option value="">Todos</option>
                        <option value="si">✅ Con Wi-Fi</option>
                        <option value="no">❌ Sin Wi-Fi</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Precio (€)</label>
                    <div class="filter-row">
                        <input type="number" id="minPrecio" placeholder="Mínimo" min="0">
                        <input type="number" id="maxPrecio" placeholder="Máximo" min="0">
                    </div>
                </div>

                <button class="btn-filtrar" onclick="aplicarFiltros()">Aplicar filtros</button>
                <button class="btn-limpiar" onclick="limpiarFiltros()">Limpiar filtros</button>
            </div>

            <!-- Panel de resultados -->
            <div class="results-panel">
                <div class="search-box">
                    <input type="text" id="busqueda" placeholder="Buscar por nombre de placa base..." onkeyup="buscarConDebounce()">
                </div>

                <div class="results-header">
                    <h2>📋 Resultados</h2>
                    <span class="results-count" id="resultadosCount">0</span>
                </div>

                <div class="loading" id="loading">Cargando placas base</div>

                <div id="gridPlacas" class="grid-placas"></div>

                <div id="noResults" class="no-results" style="display: none;">
                    <i>🔍</i>
                    <h3>No se encontraron placas base</h3>
                    <p>Prueba con otros filtros o búsqueda</p>
                </div>

                <a href="index.php" class="volver-btn">← Volver al menú principal</a>
            </div>
        </div>
    </div>

    <script>
        let timeoutId;
        let placaSeleccionada = null;

        // Cargar filtros al iniciar
        window.onload = function() {
            cargarFiltros();
            buscarPlacas();
        };

        function cargarFiltros() {
            fetch('buscar_placas_api.php?accion=filtros')
                .then(response => response.json())
                .then(data => {
                    llenarSelect('filtroSocket', data.sockets);
                    llenarSelect('filtroChipset', data.chipsets);
                    llenarSelect('filtroFormato', data.formatos);
                    llenarSelect('filtroTipoRAM', data.tipos_ram);
                    llenarSelect('filtroMarca', data.marcas);
                });
        }

        function llenarSelect(id, valores) {
            const select = document.getElementById(id);
            valores.forEach(valor => {
                const option = document.createElement('option');
                option.value = valor;
                option.textContent = valor;
                select.appendChild(option);
            });
        }

        function buscarConDebounce() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(buscarPlacas, 300);
        }

        function buscarPlacas() {
            const busqueda = document.getElementById('busqueda').value;
            const socket = document.getElementById('filtroSocket').value;
            const chipset = document.getElementById('filtroChipset').value;
            const formato = document.getElementById('filtroFormato').value;
            const tipo_ram = document.getElementById('filtroTipoRAM').value;
            const marca = document.getElementById('filtroMarca').value;
            const gama = document.getElementById('filtroGama').value;
            const wifi = document.getElementById('filtroWifi').value;
            const minPrecio = document.getElementById('minPrecio').value;
            const maxPrecio = document.getElementById('maxPrecio').value;

            let url = `buscar_placas_api.php?q=${encodeURIComponent(busqueda)}`;
            if (socket) url += `&socket=${encodeURIComponent(socket)}`;
            if (chipset) url += `&chipset=${encodeURIComponent(chipset)}`;
            if (formato) url += `&formato=${encodeURIComponent(formato)}`;
            if (tipo_ram) url += `&tipo_ram=${encodeURIComponent(tipo_ram)}`;
            if (marca) url += `&marca=${encodeURIComponent(marca)}`;
            if (gama) url += `&gama=${encodeURIComponent(gama)}`;
            if (wifi) url += `&wifi=${encodeURIComponent(wifi)}`;
            if (minPrecio) url += `&min_precio=${minPrecio}`;
            if (maxPrecio) url += `&max_precio=${maxPrecio}`;

            document.getElementById('loading').style.display = 'block';
            document.getElementById('gridPlacas').innerHTML = '';
            document.getElementById('noResults').style.display = 'none';

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('resultadosCount').textContent = data.length;
                    
                    if (data.length === 0) {
                        document.getElementById('noResults').style.display = 'block';
                    } else {
                        mostrarPlacas(data);
                    }
                });
        }

        function mostrarPlacas(placas) {
            const grid = document.getElementById('gridPlacas');
            grid.innerHTML = '';

            placas.forEach(placa => {
                const card = document.createElement('div');
                card.className = `placa-card ${placaSeleccionada === placa.id ? 'seleccionada' : ''}`;
                
                const gamaClass = placa.gama === 'Alta' ? 'gama-alta' : (placa.gama === 'Media' ? 'gama-media' : 'gama-baja');
                
                card.innerHTML = `
                    ${placa.wifi ? '<span class="badge-wifi">📶 Wi-Fi</span>' : ''}
                    <div class="placa-header">
                        <span class="placa-marca">${placa.marca || 'Generic'}</span>
                        <span class="gama-badge ${gamaClass}">${placa.gama}</span>
                    </div>
                    <div class="placa-nombre">${placa.nombre}</div>
                    
                    <div class="compatibilidad-grid">
                        <div class="compat-item">
                            <div class="compat-label">Socket</div>
                            <div class="compat-value">${placa.socket}</div>
                        </div>
                        <div class="compat-item">
                            <div class="compat-label">Chipset</div>
                            <div class="compat-value">${placa.chipset}</div>
                        </div>
                        <div class="compat-item">
                            <div class="compat-label">Formato</div>
                            <div class="compat-value">${placa.formato}</div>
                        </div>
                        <div class="compat-item">
                            <div class="compat-label">RAM</div>
                            <div class="compat-value">${placa.tipo_ram}</div>
                        </div>
                        <div class="compat-item">
                            <div class="compat-label">Slots RAM</div>
                            <div class="compat-value">${placa.slots_ram || '?'}</div>
                        </div>
                        <div class="compat-item">
                            <div class="compat-label">Max RAM</div>
                            <div class="compat-value">${placa.max_ram || '?'}GB</div>
                        </div>
                        <div class="compat-item">
                            <div class="compat-label">M.2</div>
                            <div class="compat-value">${placa.m2_slots || '0'}</div>
                        </div>
                        <div class="compat-item">
                            <div class="compat-label">SATA</div>
                            <div class="compat-value">${placa.sata_ports || '0'}</div>
                        </div>
                        <div class="compat-item">
                            <div class="compat-label">PCIe</div>
                            <div class="compat-value">${placa.pcie_version || '?'}</div>
                        </div>
                    </div>
                    
                    <div class="especificaciones">${placa.especificaciones || 'Sin especificaciones'}</div>
                    
                    <div class="placa-footer">
                        <span class="precio">€${parseFloat(placa.precio).toFixed(2)}</span>
                        <button class="btn-seleccionar" onclick="seleccionarPlaca(${placa.id})">Seleccionar</button>
                    </div>
                `;
                
                grid.appendChild(card);
            });
        }

        function seleccionarPlaca(id) {
            placaSeleccionada = id;
            // Aquí puedes guardar la selección en localStorage o enviarla a otra página
            localStorage.setItem('placaSeleccionada', id);
            
            // Actualizar UI
            document.querySelectorAll('.placa-card').forEach(card => {
                card.classList.remove('seleccionada');
            });
            event.target.closest('.placa-card').classList.add('seleccionada');
            
            alert('✅ Placa base seleccionada correctamente');
            
            // Redirigir después de 1 segundo
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1000);
        }

        function aplicarFiltros() {
            buscarPlacas();
        }

        function limpiarFiltros() {
            document.getElementById('busqueda').value = '';
            document.getElementById('filtroSocket').value = '';
            document.getElementById('filtroChipset').value = '';
            document.getElementById('filtroFormato').value = '';
            document.getElementById('filtroTipoRAM').value = '';
            document.getElementById('filtroMarca').value = '';
            document.getElementById('filtroGama').value = '';
            document.getElementById('filtroWifi').value = '';
            document.getElementById('minPrecio').value = '';
            document.getElementById('maxPrecio').value = '';
            buscarPlacas();
        }
    </script>
</body>
</html>
