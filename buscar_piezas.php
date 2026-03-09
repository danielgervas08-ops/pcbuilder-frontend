<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Piezas - Recomendaciones</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        
        .info-pieza {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .grid-recomendaciones {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .tarjeta {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .tarjeta:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-color: #667eea;
        }
        
        .gama {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .gama-alta { background: #ff4757; color: white; }
        .gama-media { background: #ffa502; color: white; }
        .gama-baja { background: #26de81; color: white; }
        
        .precio {
            font-size: 1.3em;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }
        
        .btn-volver {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        
        .btn-volver:hover {
            background: #764ba2;
        }
        
        .loading {
            text-align: center;
            padding: 50px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        $componente = $_GET['componente'] ?? '';
        $modelo = $_GET['modelo'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        
        echo "<h1>🔍 Recomendaciones para tu $componente</h1>";
        echo "<div class='info-pieza'>";
        echo "<p><strong>Componente actual:</strong> $modelo</p>";
        echo "<p><strong>Buscando:</strong> " . ($tipo == 'mejorar' ? '⬆️ Mejora' : ($tipo == 'reemplazar' ? '🔄 Reemplazo' : '⬇️ Económico')) . "</p>";
        echo "</div>";
        
        // Aquí irá la conexión a la base de datos para mostrar recomendaciones
        echo "<div class='grid-recomendaciones'>";
        
        // Ejemplo de cómo se mostrarían los resultados
        if ($componente == 'cpu') {
            // Mostrar procesadores recomendados
            echo "<div class='tarjeta'>";
            echo "<span class='gama gama-alta'>GAMA ALTA</span>";
            echo "<h3>Intel Core i7-13700K</h3>";
            echo "<p>16 núcleos, 24 hilos, 5.4GHz</p>";
            echo "<div class='precio'>€419.00</div>";
            echo "<button class='btn'>Seleccionar</button>";
            echo "</div>";
            
            echo "<div class='tarjeta'>";
            echo "<span class='gama gama-media'>GAMA MEDIA</span>";
            echo "<h3>Intel Core i5-13600K</h3>";
            echo "<p>14 núcleos, 20 hilos, 5.1GHz</p>";
            echo "<div class='precio'>€299.00</div>";
            echo "<button class='btn'>Seleccionar</button>";
            echo "</div>";
        }
        
        echo "</div>";
        ?>
        
        <a href="index.php" class="btn-volver">← Volver al menú principal</a>
    </div>
</body>
</html>
