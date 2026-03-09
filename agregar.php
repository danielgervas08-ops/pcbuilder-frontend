<!DOCTYPE html>
<html>
<head>
    <title>Añadir Componente</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f5f5f5; }
        .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; }
        label { display: block; margin-top: 15px; font-weight: bold; color: #555; }
        input, select, textarea { 
            width: 100%; 
            padding: 10px; 
            margin-top: 5px; 
            border: 1px solid #ddd; 
            border-radius: 5px;
            box-sizing: border-box;
        }
        button { 
            background: #4CAF50; 
            color: white; 
            padding: 12px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            width: 100%;
            margin-top: 20px;
            font-size: 16px;
        }
        button:hover { background: #45a049; }
        .mensaje { padding: 15px; margin-top: 20px; border-radius: 5px; display: none; }
        .exito { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .nav { text-align: center; margin-bottom: 20px; }
        .nav a { color: #4CAF50; text-decoration: none; margin: 0 10px; }
        .nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="index.php">🏠 Ver Componentes</a> | 
            <a href="agregar.php">➕ Añadir Nuevo</a>
        </div>
        
        <h1>➕ Añadir Nuevo Componente</h1>
        
        <?php
        // Procesar el formulario cuando se envía
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recoger datos del formulario
            $nombre = $_POST['nombre'];
            $tipo = $_POST['tipo'];
            $especificaciones = $_POST['especificaciones'];
            $precio = $_POST['precio'];
            
            // Validar que no estén vacíos
            if (empty($nombre) || empty($tipo) || empty($precio)) {
                echo "<div class='mensaje error' style='display:block'>❌ Error: Nombre, tipo y precio son obligatorios</div>";
            } else {
                // Conectar a PostgreSQL
                $host = '192.168.5.66';
                $port = '5432';
                $dbname = 'datastore_db';
                $user = 'web_user';
                $password = 'tu_contraseña_segura'; // CAMBIA ESTO
                
                $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
                
                if (!$conn) {
                    echo "<div class='mensaje error' style='display:block'>❌ Error de conexión a la base de datos</div>";
                } else {
                    // Insertar datos (usando consulta preparada para seguridad)
                    $query = "INSERT INTO componentes (nombre, tipo, especificaciones, precio) VALUES ($1, $2, $3, $4)";
                    $result = pg_query_params($conn, $query, array($nombre, $tipo, $especificaciones, $precio));
                    
                    if ($result) {
                        echo "<div class='mensaje exito' style='display:block'>✅ Componente añadido correctamente</div>";
                    } else {
                        echo "<div class='mensaje error' style='display:block'>❌ Error al añadir: " . pg_last_error($conn) . "</div>";
                    }
                    
                    pg_close($conn);
                }
            }
        }
        ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="nombre">Nombre del componente:</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Ej: Intel i7-13700K">
            
            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <option value="">Selecciona un tipo</option>
                <option value="CPU">CPU (Procesador)</option>
                <option value="GPU">GPU (Tarjeta gráfica)</option>
                <option value="RAM">RAM (Memoria)</option>
                <option value="SSD">SSD (Disco sólido)</option>
                <option value="HDD">HDD (Disco duro)</option>
                <option value="Placa Base">Placa Base</option>
                <option value="Fuente">Fuente de alimentación</option>
                <option value="Refrigeración">Refrigeración</option>
                <option value="Caja">Caja/Torre</option>
            </select>
            
            <label for="especificaciones">Especificaciones técnicas:</label>
            <textarea id="especificaciones" name="especificaciones" rows="4" placeholder="Detalla las especificaciones..."></textarea>
            
            <label for="precio">Precio (€):</label>
            <input type="number" id="precio" name="precio" step="0.01" required placeholder="0.00">
            
            <button type="submit">Añadir Componente</button>
        </form>
        
        <p style="text-align:center; margin-top:20px; color:#666;">
            <small>Los campos con * son obligatorios</small>
        </p>
    </div>
</body>
</html>
