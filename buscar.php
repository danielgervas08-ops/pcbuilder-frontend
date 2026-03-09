<?php
// buscar.php - Devuelve sugerencias de componentes en JSON
header('Content-Type: application/json');

// Configuración de conexión
$host = '192.168.5.66';
$port = '5432';
$dbname = 'datastore_db';
$user = 'web_user';
$password = '123456';  // CAMBIA POR TU CONTRASEÑA

// Obtener el término de búsqueda
$termino = isset($_GET['q']) ? $_GET['q'] : '';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'todos';

if (strlen($termino) < 1) {
    echo json_encode([]);
    exit;
}

// Conectar a PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

$resultados = [];

// Buscar en procesadores
if ($tipo == 'todos' || $tipo == 'cpu') {
    $query = "SELECT id, nombre, gama, precio, 'CPU' as tipo_componente, 
              nucleos || ' núcleos, ' || hilos || ' hilos' as especificaciones_resumidas
              FROM procesadores 
              WHERE nombre ILIKE $1 
              ORDER BY precio DESC 
              LIMIT 5";
    $result = pg_query_params($conn, $query, array('%' . $termino . '%'));
    
    while ($row = pg_fetch_assoc($result)) {
        $row['icono'] = '🖥️';
        $resultados[] = $row;
    }
}

// Buscar en gráficas
if ($tipo == 'todos' || $tipo == 'gpu') {
    $query = "SELECT id, nombre, gama, precio, 'GPU' as tipo_componente,
              vram || 'GB ' || tipo_vram || ', ' || nucleos_cuda || ' CUDA' as especificaciones_resumidas
              FROM graficas 
              WHERE nombre ILIKE $1 
              ORDER BY precio DESC 
              LIMIT 5";
    $result = pg_query_params($conn, $query, array('%' . $termino . '%'));
    
    while ($row = pg_fetch_assoc($result)) {
        $row['icono'] = '🎮';
        $resultados[] = $row;
    }
}

// Buscar en RAM
if ($tipo == 'todos' || $tipo == 'ram') {
    $query = "SELECT id, nombre, gama, precio, 'RAM' as tipo_componente,
              capacidad || 'GB ' || tipo || ' ' || velocidad || 'MHz' as especificaciones_resumidas
              FROM ram 
              WHERE nombre ILIKE $1 
              ORDER BY precio DESC 
              LIMIT 5";
    $result = pg_query_params($conn, $query, array('%' . $termino . '%'));
    
    while ($row = pg_fetch_assoc($result)) {
        $row['icono'] = '💾';
        $resultados[] = $row;
    }
}

// Buscar en SSD
if ($tipo == 'todos' || $tipo == 'ssd') {
    $query = "SELECT id, nombre, gama, precio, 'SSD' as tipo_componente,
              capacidad || 'GB ' || tipo || ', ' || lectura || 'MB/s lectura' as especificaciones_resumidas
              FROM ssd 
              WHERE nombre ILIKE $1 
              ORDER BY precio DESC 
              LIMIT 5";
    $result = pg_query_params($conn, $query, array('%' . $termino . '%'));
    
    while ($row = pg_fetch_assoc($result)) {
        $row['icono'] = '⚡';
        $resultados[] = $row;
    }
}

// Buscar en HDD
if ($tipo == 'todos' || $tipo == 'hdd') {
    $query = "SELECT id, nombre, gama, precio, 'HDD' as tipo_componente,
              capacidad || 'GB, ' || rpm || 'rpm' as especificaciones_resumidas
              FROM hdd 
              WHERE nombre ILIKE $1 
              ORDER BY precio DESC 
              LIMIT 5";
    $result = pg_query_params($conn, $query, array('%' . $termino . '%'));
    
    while ($row = pg_fetch_assoc($result)) {
        $row['icono'] = '💿';
        $resultados[] = $row;
    }
}

// Buscar en placas base
if ($tipo == 'todos' || $tipo == 'placa') {
    $query = "SELECT id, nombre, gama, precio, 'Placa Base' as tipo_componente,
              socket || ', ' || chipset || ', ' || formato as especificaciones_resumidas
              FROM placas_base 
              WHERE nombre ILIKE $1 
              ORDER BY precio DESC 
              LIMIT 5";
    $result = pg_query_params($conn, $query, array('%' . $termino . '%'));
    
    while ($row = pg_fetch_assoc($result)) {
        $row['icono'] = '🔧';
        $resultados[] = $row;
    }
}

// Ordenar resultados por relevancia (coincidencia exacta al principio)
usort($resultados, function($a, $b) use ($termino) {
    $a_match = stripos($a['nombre'], $termino) === 0 ? 0 : 1;
    $b_match = stripos($b['nombre'], $termino) === 0 ? 0 : 1;
    return $a_match - $b_match;
});

pg_close($conn);
echo json_encode(array_slice($resultados, 0, 10));
?>
