<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montar Nuevo PC</title>
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
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
        
        .selector-componente {
            margin-bottom: 30px;
            padding: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
        }
        
        .selector-componente h3 {
            margin-bottom: 15px;
            color: #555;
        }
        
        select, input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1em;
            margin-bottom: 15px;
        }
        
        .btn-buscar {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>🖥️ MONTAR NUEVO ORDENADOR</h1>
        
        <div class="selector-componente">
            <h3>1. Selecciona el procesador (CPU)</h3>
            <input type="text" placeholder="Buscar procesador...">
            <button class="btn-buscar">Buscar</button>
        </div>
        
        <div class="selector-componente">
            <h3>2. Selecciona la placa base</h3>
            <input type="text" placeholder="Buscar placa base...">
            <button class="btn-buscar">Buscar</button>
        </div>
        
        <div class="selector-componente">
            <h3>3. Selecciona la memoria RAM</h3>
            <input type="text" placeholder="Buscar RAM...">
            <button class="btn-buscar">Buscar</button>
        </div>
        
        <div class="selector-componente">
            <h3>4. Selecciona la tarjeta gráfica</h3>
            <input type="text" placeholder="Buscar gráfica...">
            <button class="btn-buscar">Buscar</button>
        </div>
        
        <a href="index.php" class="btn-volver">← Volver al menú principal</a>
    </div>
</body>
</html>
