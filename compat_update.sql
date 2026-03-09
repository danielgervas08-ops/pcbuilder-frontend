-- ══════════════════════════════════════════════════════════════════════
--  ACTUALIZACIÓN DE TABLAS — Compatibilidad completa
--  Ejecutar: psql -h 192.168.5.66 -U web_user -d datastore_db -f compat_update.sql
-- ══════════════════════════════════════════════════════════════════════

-- ─── PLACAS BASE — añadir info de slots disponibles ─────────────────────────
ALTER TABLE motherboards
  ADD COLUMN IF NOT EXISTS pcie_version    VARCHAR(10),      -- PCIe 4.0, PCIe 5.0
  ADD COLUMN IF NOT EXISTS m2_slots        INT DEFAULT 0,    -- número de ranuras M.2
  ADD COLUMN IF NOT EXISTS m2_pcie         BOOLEAN DEFAULT FALSE,  -- soporta NVMe M.2
  ADD COLUMN IF NOT EXISTS m2_sata         BOOLEAN DEFAULT FALSE,  -- soporta SATA M.2
  ADD COLUMN IF NOT EXISTS sata_ports      INT DEFAULT 0,    -- puertos SATA disponibles
  ADD COLUMN IF NOT EXISTS max_memory_gb   INT DEFAULT 128,  -- RAM máxima soportada
  ADD COLUMN IF NOT EXISTS memory_slots    INT DEFAULT 4;    -- ranuras de RAM

UPDATE motherboards SET
  pcie_version='PCIe 5.0', m2_slots=4, m2_pcie=TRUE, m2_sata=TRUE,
  sata_ports=6, max_memory_gb=128, memory_slots=4
WHERE name = 'ROG Strix B650E-F Gaming';

UPDATE motherboards SET
  pcie_version='PCIe 5.0', m2_slots=3, m2_pcie=TRUE, m2_sata=TRUE,
  sata_ports=6, max_memory_gb=128, memory_slots=4
WHERE name = 'MAG B650 Tomahawk WiFi';

UPDATE motherboards SET
  pcie_version='PCIe 5.0', m2_slots=2, m2_pcie=TRUE, m2_sata=FALSE,
  sata_ports=4, max_memory_gb=128, memory_slots=4
WHERE name = 'B650M Pro RS';

UPDATE motherboards SET
  pcie_version='PCIe 5.0', m2_slots=4, m2_pcie=TRUE, m2_sata=TRUE,
  sata_ports=6, max_memory_gb=128, memory_slots=4
WHERE name = 'Z790 Aorus Elite AX';

UPDATE motherboards SET
  pcie_version='PCIe 5.0', m2_slots=3, m2_pcie=TRUE, m2_sata=TRUE,
  sata_ports=6, max_memory_gb=128, memory_slots=4
WHERE name = 'Prime Z790-P WiFi';

UPDATE motherboards SET
  pcie_version='PCIe 4.0', m2_slots=3, m2_pcie=TRUE, m2_sata=TRUE,
  sata_ports=6, max_memory_gb=128, memory_slots=4
WHERE name = 'MAG Z790 Tomahawk WiFi';

UPDATE motherboards SET
  pcie_version='PCIe 4.0', m2_slots=2, m2_pcie=TRUE, m2_sata=TRUE,
  sata_ports=6, max_memory_gb=128, memory_slots=4
WHERE name = 'B550 Gaming X V2';

UPDATE motherboards SET
  pcie_version='PCIe 4.0', m2_slots=2, m2_pcie=TRUE, m2_sata=TRUE,
  sata_ports=4, max_memory_gb=128, memory_slots=4
WHERE name = 'B550M DS3H';


-- ─── GPUs — añadir versión PCIe requerida ────────────────────────────────────
ALTER TABLE gpus
  ADD COLUMN IF NOT EXISTS pcie_version VARCHAR(10) DEFAULT 'PCIe 4.0';

UPDATE gpus SET pcie_version='PCIe 4.0' WHERE name LIKE 'RTX 4090%';
UPDATE gpus SET pcie_version='PCIe 4.0' WHERE name LIKE 'RTX 4080%';
UPDATE gpus SET pcie_version='PCIe 4.0' WHERE name LIKE 'RTX 4070%';
UPDATE gpus SET pcie_version='PCIe 4.0' WHERE name LIKE 'RTX 4060%';
UPDATE gpus SET pcie_version='PCIe 4.0' WHERE name LIKE 'RX 7%';
UPDATE gpus SET pcie_version='PCIe 4.0' WHERE name LIKE 'Arc%';


-- ─── ALMACENAMIENTO — añadir tipo de interfaz ────────────────────────────────
ALTER TABLE storage
  ADD COLUMN IF NOT EXISTS interface VARCHAR(20) DEFAULT 'SATA';
  -- Valores: NVMe-PCIe4, NVMe-PCIe3, SATA, HDD-SATA

UPDATE storage SET interface='NVMe-PCIe4' WHERE type='NVMe';
UPDATE storage SET interface='SATA'       WHERE type='SSD';
UPDATE storage SET interface='HDD-SATA'  WHERE type='HDD';


-- ─── FUENTES DE ALIMENTACIÓN — añadir tipo conector GPU ──────────────────────
ALTER TABLE psus
  ADD COLUMN IF NOT EXISTS connector_16pin BOOLEAN DEFAULT FALSE,  -- 16-pin (RTX 4000)
  ADD COLUMN IF NOT EXISTS connector_8pin  INT DEFAULT 2;          -- cuántos 8-pin PCIe tiene

UPDATE psus SET connector_16pin=TRUE,  connector_8pin=2 WHERE watts >= 850;
UPDATE psus SET connector_16pin=FALSE, connector_8pin=2 WHERE watts < 850 AND watts >= 650;
UPDATE psus SET connector_16pin=FALSE, connector_8pin=1 WHERE watts < 650;


-- ─── CAJAS — añadir compatibilidad GPU y refrigeración ───────────────────────
ALTER TABLE cases
  ADD COLUMN IF NOT EXISTS max_gpu_mm       INT DEFAULT 380,   -- longitud máx GPU en mm
  ADD COLUMN IF NOT EXISTS max_cooler_mm    INT DEFAULT 160,   -- altura máx disipador aire
  ADD COLUMN IF NOT EXISTS radiator_support VARCHAR(50) DEFAULT '240,280,360'; -- AIOs soportados

UPDATE cases SET max_gpu_mm=380, max_cooler_mm=170, radiator_support='240,280,360' WHERE name='4000D Airflow';
UPDATE cases SET max_gpu_mm=420, max_cooler_mm=185, radiator_support='240,280,360' WHERE name='5000D Airflow';
UPDATE cases SET max_gpu_mm=325, max_cooler_mm=165, radiator_support='240,280'     WHERE name='H510';
UPDATE cases SET max_gpu_mm=381, max_cooler_mm=185, radiator_support='240,280,360' WHERE name='H7 Flow';
UPDATE cases SET max_gpu_mm=491, max_cooler_mm=185, radiator_support='240,280,360' WHERE name='Meshify 2';
UPDATE cases SET max_gpu_mm=440, max_cooler_mm=185, radiator_support='240,280,360' WHERE name='Define 7';
UPDATE cases SET max_gpu_mm=330, max_cooler_mm=165, radiator_support='240,280'     WHERE name='MAG Forge 320R Airflow';
UPDATE cases SET max_gpu_mm=369, max_cooler_mm=190, radiator_support='240,280,360' WHERE name='Pure Base 500DX';


-- ─── REFRIGERACIÓN — añadir tamaño del radiador y altura del disipador ───────
ALTER TABLE coolers
  ADD COLUMN IF NOT EXISTS radiator_mm  INT DEFAULT 0,    -- 0 si es aire, 240/280/360 si es AIO
  ADD COLUMN IF NOT EXISTS height_mm    INT DEFAULT 0;    -- altura si es disipador de aire

UPDATE coolers SET radiator_mm=0,   height_mm=165 WHERE name='NH-D15';
UPDATE coolers SET radiator_mm=0,   height_mm=125 WHERE name='NH-U12S redux';
UPDATE coolers SET radiator_mm=0,   height_mm=162 WHERE name='Dark Rock Pro 4';
UPDATE coolers SET radiator_mm=0,   height_mm=159 WHERE name='Hyper 212 Black';
UPDATE coolers SET radiator_mm=280, height_mm=0   WHERE name='Kraken X63 280mm AIO';
UPDATE coolers SET radiator_mm=360, height_mm=0   WHERE name='Kraken X73 360mm AIO';
UPDATE coolers SET radiator_mm=360, height_mm=0   WHERE name='H150i Elite 360mm AIO';
UPDATE coolers SET radiator_mm=280, height_mm=0   WHERE name='H115i Elite 280mm AIO';
