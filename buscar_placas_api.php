<?php
// buscar_placas_api.php - API para sugerencias de placas base
header('Content-Type: application/json');

// Configuración de conexión
$host = '192.168.5.66';
$port = '5432';
$dbname = 'datastore_db';
$user = 'web_user';
$password = '123456';  // ← CAMBIA AQUÍ TU CONTRASEÑA

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

$accion = $_GET['accion'] ?? 'buscar';

if ($accion == 'filtros') {
    // Obtener valores únicos para los filtros
    $filtros = [];
    
    $queries = [
        'sockets' => "SELECT DISTINCT socket FROM placas_base WHERE socket IS NOT NULL ORDER BY socket",
        'chipsets' => "SELECT DISTINCT chipset FROM placas_base WHERE chipset IS NOT NULL ORDER BY chipset",
        'formatos' => "SELECT DISTINCT formato FROM placas_base WHERE formato IS NOT NULL ORDER BY formato",
        'tipos_ram' => "SELECT DISTINCT tipo_ram FROM placas_base WHERE tipo_ram IS NOT NULL ORDER BY tipo_ram",
        'marcas' => "SELECT DISTINCT marca FROM placas_base WHERE marca IS NOT NULL ORDER BY marca",
        'gamas' => "SELECT DISTINCT gama FROM placas_base WHERE gama IS NOT NULL ORDER BY gama"
    ];
    
    foreach ($queries as $key => $query) {
        $result = pg_query($conn, $query);
        $filtros[$key] = [];
        while ($row = pg_fetch_assoc($result)) {
            $filtros[$key][] = $row[array_key_first($row)];
        }
    }
    
    echo json_encode($filtros);
    
} else {
    // Búsqueda de placas
    $termino = $_GET['q'] ?? '';
    $socket = $_GET['socket'] ?? '';
    $chipset = $_GET['chipset'] ?? '';
    $formato = $_GET['formato'] ?? '';
    $tipo_ram = $_GET['tipo_ram'] ?? '';
    $marca = $_GET['marca'] ?? '';
    $gama = $_GET['gama'] ?? '';
    $min_precio = $_GET['min_precio'] ?? '';
    $max_precio = $_GET['max_precio'] ?? '';
    $wifi = $_GET['wifi'] ?? '';
    
    $params = [];
    $condiciones = [];
    
    if (!empty($termino)) {
        $condiciones[] = "nombre ILIKE $" . (count($params) + 1);
        $params[] = '%' . $termino . '%';
    }
    
    if (!empty($socket)) {
        $condiciones[] = "socket = $" . (count($params) + 1);
        $params[] = $socket;
    }
    
    if (!empty($chipset)) {
        $condiciones[] = "chipset = $" . (count($params) + 1);
        $params[] = $chipset;
    }
    
    if (!empty($formato)) {
        $condiciones[] = "formato = $" . (count($params) + 1);
        $params[] = $formato;
    }
    
    if (!empty($tipo_ram)) {
        $condiciones[] = "tipo_ram = $" . (count($params) + 1);
  <?php
// buscar_placas_api.php - API para sugerencias de placas base
header('Content-Type: application/json');

// Configuración de conexión
$host = '192.168.5.66';
$port = '5432';
$dbname = 'datastore_db';
$user = 'web_user';
$password = '271205';  // ← CAMBIA AQUÍ TU CONTRASEÑA

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

$accion = $_GET['accion'] ?? 'buscar';

if ($accion == 'filtros') {
    // Obtener valores únicos para los filtros
    $filtros = [];
    
    $queries = [
        'sockets' => "SELECT DISTINCT socket FROM placas_base WHERE socket IS NOT NULL ORDER BY socket",
        'chipsets' => "SELECT DISTINCT chipset FROM placas_base WHERE chipset IS NOT NULL ORDER BY chipset",
        'formatos' => "SELECT DISTINCT formato FROM placas_base WHERE formato IS NOT NULL ORDER BY formato",
        'tipos_ram' => "SELECT DISTINCT tipo_ram FROM placas_base WHERE tipo_ram IS NOT NULL ORDER BY tipo_ram",
        'marcas' => "SELECT DISTINCT marca FROM placas_base WHERE marca IS NOT NULL ORDER BY marca",
        'gamas' => "SELECT DISTINCT gama FROM placas_base WHERE gama IS NOT NULL ORDER BY gama"
    ];
    
    foreach ($queries as $key => $query) {
        $result = pg_query($conn, $query);
        $filtros[$key] = [];
        while ($row = pg_fetch_assoc($result)) {
            $filtros[$key][] = $row[array_key_first($row)];
        }
    }
    
    echo json_encode($filtros);
    
} else {
    // Búsqueda de placas
    $termino = $_GET['q'] ?? '';
    $socket = $_GET['socket'] ?? '';
    $chipset = $_GET['chipset'] ?? '';
    $formato = $_GET['formato'] ?? '';
    $tipo_ram = $_GET['tipo_ram'] ?? '';
    $marca = $_GET['marca'] ?? '';
    $gama = $_GET['gama'] ?? '';
    $min_precio = $_GET['min_precio'] ?? '';
    $max_precio = $_GET['max_precio'] ?? '';
    $wifi = $_GET['wifi'] ?? '';
    
    $params = [];
    $condiciones = [];
    
    if (!empty($termino)) {
        $condiciones[] = "nombre ILIKE $" . (count($params) + 1);
        $params[] = '%' . $termino . '%';
    }
    
    if (!empty($socket)) {
        $condiciones[] = "socket = $" . (count($params) + 1);
        $params[] = $socket;
    }
    
    if (!empty($chipset)) {
        $condiciones[] = "chipset = $" . (count($params) + 1);
        $params[] = $chipset;
    }
    
    if (!empty($formato)) {
        $condiciones[] = "formato = $" . (count($params) + 1);
        $params[] = $formato;
    }
    
    if (!empty($tipo_ram)) {
        $condiciones[] = "tipo_ram = $" . (count($params) + 1);
        $params[] = $tipo_ram;
    }
    
    if (!empty($marca)) {
        $condiciones[] = "marca = $" . (count($params) + 1);
        $params[] = $marca;
    }
    
    if (!empty($gama)) {
        $condiciones[] = "gama = $" . (count($params) + 1);
        $params[] = $gama;
    }
    
    if (!empty($min_precio)) {
        $condiciones[] = "precio >= $" . (count($params) + 1);
        $params[] = $min_precio;
    }
    
    if (!empty($max_precio)) {
        $condiciones[] = "precio <= $" . (count($params) + 1);
        $params[] = $max_precio;
    }
    
    if ($wifi === 'si') {
        $condiciones[] = "wifi = true";
    } elseif ($wifi === 'no') {
        $condiciones[] = "wifi = false";
    }
    
    $where = empty($condiciones) ? "" : "WHERE " . implode(" AND ", $condiciones);
    
    $query = "SELECT 
              id, nombre, marca, gama, precio,
              socket, chipset, tipo_ram, max_ram, slots_ram,
              formato, pcie_version, m2_slots, sata_ports,
              wifi, rgb_header,
              especificaciones
              FROM placas_base 
              $where 
              ORDER BY 
                  CASE gama
                      WHEN 'Alta' THEN 1
                      WHEN 'Media' THEN 2
                      WHEN 'Baja' THEN 3
                  END,
                  precio DESC 
              LIMIT 20";
    
    $result = pg_query_params($conn, $query, $params);
    
    $placas = [];
    while ($row = pg_fetch_assoc($result)) {
        $row['icono'] = '🔧';
        $placas[] = $row;
    }
    
    echo json_encode($placas);
}

pg_close($conn);
?>      $params[] = $tipo_ram;
    }
    
    if (!empty($marca)) {
        $condiciones[] = "marca = $" . (count($params) + 1);
        $params[] = $marca;
    }
    
    if (!empty($gama)) {
        $condiciones[] = "gama = $" . (count($params) + 1);
        $params[] = $gama;
    }
    
    if (!empty($min_precio)) {
        $condiciones[] = "precio >= $" . (count($params) + 1);
        $params[] = $min_precio;
    }
    
    if (!empty($max_precio)) {
        $condiciones[] = "precio <= $" . (count($params) + 1);
        $params[] = $max_precio;
    }
    
    if ($wifi === 'si') {
        $condiciones[] = "wifi = true";
    } elseif ($wifi === 'no') {
        $condiciones[] = "wifi = false";
    }
    
    $where = empty($condiciones) ? "" : "WHERE " . implode(" AND ", $condiciones);
    
    $query = "SELECT 
              id, nombre, marca, gama, precio,
              socket, chipset, tipo_ram, max_ram, slots_ram,
              formato, pcie_version, m2_slots, sata_ports,
              wifi, rgb_header,
              especificaciones
              FROM placas_base 
              $where 
              ORDER BY 
                  CASE gama
                      WHEN 'Alta' THEN 1
                      WHEN 'Media' THEN 2
                      WHEN 'Baja' THEN 3
                  END,
                  precio DESC 
              LIMIT 20";
    
    $result = pg_query_params($conn, $query, $params);
    
    $placas = [];
    while ($row = pg_fetch_assoc($result)) {
        $row['icono'] = '🔧';
        $placas[] = $row;
    }
    
    echo json_encode($placas);
}

pg_close($conn);
?>
