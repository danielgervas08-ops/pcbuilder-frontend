<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurador de PC - Menú Principal</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            width: 100%;
            text-align: center;
        }
        
        h1 {
            color: white;
            font-size: 3em;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .subtitulo {
            color: rgba(255,255,255,0.9);
            font-size: 1.2em;
            margin-bottom: 50px;
        }
        
        .menu {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .opcion {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            flex: 1;
            min-width: 280px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: 3px solid transparent;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .opcion:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border-color: #667eea;
        }
        
        .icono {
            font-size: 5em;
            margin-bottom: 20px;
        }
        
        .opcion h2 {
            font-size: 2em;
            margin-bottom: 15px;
            color: #333;
        }
        
        .opcion p {
            color: #666;
            margin-bottom: 25px;
            font-size: 1.1em;
            line-height: 1.5;
        }
        
        .btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            display: inline-block;
        }
        
        .btn:hover {
            background: #764ba2;
        }
        
        .footer {
            margin-top: 50px;
            color: rgba(255,255,255,0.8);
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 CONFIGURADOR DE PC</h1>
        <div class="subtitulo">¿Qué quieres hacer hoy?</div>
        
        <div class="menu">
            <!-- Opción 1: Montar ordenador nuevo -->
            <a href="nuevo_pc.php" class="opcion">
                <div class="icono">🖥️</div>
                <h2>OPCIÓN 1</h2>
                <p>Montar un ordenador <strong>nuevo</strong> desde cero<br>
                <span style="color: #28a745; font-size: 0.9em;">✓ Elige todos los componentes</span></p>
                <span class="btn">Comenzar</span>
            </a>
            
            <!-- Opción 2: Cambiar pieza -->
            <a href="cambiar_pieza.php" class="opcion">
                <div class="icono">🔧</div>
                <h2>OPCIÓN 2</h2>
                <p>Cambiar una <strong>pieza</strong> de mi PC actual<br>
                <span style="color: #ffa502; font-size: 0.9em;">✓ Mejorar o reemplazar</span></p>
                <span class="btn">Seleccionar pieza</span>
            </a>
        </div>
        
        <div class="footer">
            <p>Selecciona una opción para comenzar</p>
        </div>
    </div>
</body>
</html>
