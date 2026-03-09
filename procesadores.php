<?php
// procesadores.php - Versión de depuración
$host = '192.168.5.66';
$port = '5432';
$dbname = 'datastore_db';
$user = 'web_user';
$password = '123456';  // CAMBIA AQUÍ TU CONTRASEÑA

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test Procesadores</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 10px; }
        .success { color: green; background: #e8f5e9; padding: 10px; border-radius: 5px; }
        .error { color: red; background: #ffebee; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #4CAF50; color: white; padding: 10px; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Test de Procesadores</h1>";

// Probar conexión
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo "<div class='error'>❌ Error de conexión: " . pg_last_error() . "</div>";
} else {
    echo "<div class='success'>✅ Conexión exitosa a PostgreSQL</div>";
    
    // Ver si la tabla existe
    $result = pg_query($conn, "SELECT COUNT(*) as total FROM procesadores");
    if (!$result) {
        echo "<div class='error'>❌ Error en consulta: " . pg_last_error($conn) . "</div>";
    } else {
        $row = pg_fetch_assoc($result);
        echo "<p><strong>Total procesadores:</strong> " . $row['total'] . "</p>";
        
        // Mostrar los procesadores
        $result = pg_query($conn, "SELECT id, nombre, gama, precio FROM procesadores LIMIT 100");
        if (pg_num_rows($result) > 0) {
            echo "<h3>Lista de procesadores:</h3>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Gama</th><th>Precio</th></tr>";
            while ($row = pg_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                echo "<td>" . $row['gama'] . "</td>";
                echo "<td>€" . number_format($row['precio'], 2) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay procesadores en la tabla</p>";
        }
    }
    
    pg_close($conn);
}

echo "    </div>
</body>
</html>";
?>
