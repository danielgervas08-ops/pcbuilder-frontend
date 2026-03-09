-- ══════════════════════════════════════════════════════════════
--  PC BUILDER — Script completo para datastore_db
--  Ejecutar: psql -h 192.168.5.66 -U web_user -d datastore_db -f setup.sql
-- ══════════════════════════════════════════════════════════════


-- ─── PLACAS BASE ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS motherboards (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(200) NOT NULL,
    brand         VARCHAR(100),
    socket        VARCHAR(50),       -- AM5, LGA1700, AM4...
    memory_type   VARCHAR(20),       -- DDR4, DDR5
    form_factor   VARCHAR(20),       -- ATX, mATX, ITX
    price         NUMERIC(10,2)
);

INSERT INTO motherboards (name, brand, socket, memory_type, form_factor, price) VALUES
('ROG Strix B650E-F Gaming',     'ASUS',    'AM5',    'DDR5', 'ATX',  289.99),
('MAG B650 Tomahawk WiFi',       'MSI',     'AM5',    'DDR5', 'ATX',  219.99),
('B650M Pro RS',                 'ASUS',    'AM5',    'DDR5', 'mATX', 159.99),
('Z790 Aorus Elite AX',          'Gigabyte','LGA1700','DDR5', 'ATX',  299.99),
('Prime Z790-P WiFi',            'ASUS',    'LGA1700','DDR5', 'ATX',  219.99),
('MAG Z790 Tomahawk WiFi',       'MSI',     'LGA1700','DDR4', 'ATX',  249.99),
('B550 Gaming X V2',             'Gigabyte','AM4',    'DDR4', 'ATX',  109.99),
('B550M DS3H',                   'Gigabyte','AM4',    'DDR4', 'mATX',  74.99);


-- ─── CPUs ────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS cpus (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(200) NOT NULL,
    brand         VARCHAR(100),
    socket        VARCHAR(50),
    cores         INT,
    threads       INT,
    base_ghz      NUMERIC(4,2),
    boost_ghz     NUMERIC(4,2),
    price         NUMERIC(10,2)
);

INSERT INTO cpus (name, brand, socket, cores, threads, base_ghz, boost_ghz, price) VALUES
('Ryzen 9 7950X',   'AMD',   'AM5',    16, 32, 4.5, 5.7, 549.99),
('Ryzen 7 7700X',   'AMD',   'AM5',     8, 16, 4.5, 5.4, 299.99),
('Ryzen 5 7600X',   'AMD',   'AM5',     6, 12, 4.7, 5.3, 199.99),
('Ryzen 5 7600',    'AMD',   'AM5',     6, 12, 3.8, 5.1, 169.99),
('Core i9-13900K',  'Intel', 'LGA1700', 24, 32, 3.0, 5.8, 529.99),
('Core i7-13700K',  'Intel', 'LGA1700', 16, 24, 3.4, 5.4, 369.99),
('Core i5-13600K',  'Intel', 'LGA1700', 14, 20, 3.5, 5.1, 259.99),
('Core i5-13400F',  'Intel', 'LGA1700', 10, 16, 2.5, 4.6, 179.99),
('Ryzen 7 5700X',   'AMD',   'AM4',      8, 16, 3.4, 4.6, 149.99),
('Ryzen 5 5600X',   'AMD',   'AM4',      6, 12, 3.7, 4.6, 119.99);


-- ─── RAM ─────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS ram (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(200) NOT NULL,
    brand         VARCHAR(100),
    type          VARCHAR(20),    -- DDR4, DDR5
    capacity_gb   INT,
    speed_mhz     INT,
    price         NUMERIC(10,2)
);

INSERT INTO ram (name, brand, type, capacity_gb, speed_mhz, price) VALUES
('Vengeance DDR5-5600 16GB',     'Corsair',   'DDR5', 16, 5600,  89.99),
('Vengeance DDR5-5600 32GB',     'Corsair',   'DDR5', 32, 5600, 159.99),
('Trident Z5 DDR5-6000 32GB',    'G.Skill',   'DDR5', 32, 6000, 179.99),
('Fury Beast DDR5-5200 16GB',    'Kingston',  'DDR5', 16, 5200,  74.99),
('Vengeance DDR4-3200 16GB',     'Corsair',   'DDR4', 16, 3200,  44.99),
('Vengeance DDR4-3200 32GB',     'Corsair',   'DDR4', 32, 3200,  84.99),
('Ripjaws V DDR4-3600 16GB',     'G.Skill',   'DDR4', 16, 3600,  49.99),
('Fury Beast DDR4-3200 8GB',     'Kingston',  'DDR4',  8, 3200,  24.99);


-- ─── GPUs ─────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS gpus (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(200) NOT NULL,
    brand         VARCHAR(100),
    vram_gb       INT,
    tdp_w         INT,
    price         NUMERIC(10,2)
);

INSERT INTO gpus (name, brand, vram_gb, tdp_w, price) VALUES
('RTX 4090 24GB',           'NVIDIA', 24, 450, 1599.99),
('RTX 4080 Super 16GB',     'NVIDIA', 16, 320,  999.99),
('RTX 4070 Ti Super 16GB',  'NVIDIA', 16, 285,  799.99),
('RTX 4070 Super 12GB',     'NVIDIA', 12, 220,  599.99),
('RTX 4060 Ti 16GB',        'NVIDIA', 16, 165,  449.99),
('RTX 4060 8GB',            'NVIDIA',  8, 115,  299.99),
('RX 7900 XTX 24GB',        'AMD',    24, 355,  949.99),
('RX 7800 XT 16GB',         'AMD',    16, 263,  489.99),
('RX 7600 8GB',             'AMD',     8, 165,  269.99),
('Arc A770 16GB',           'Intel',  16, 225,  299.99);


-- ─── ALMACENAMIENTO ──────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS storage (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(200) NOT NULL,
    brand         VARCHAR(100),
    type          VARCHAR(20),    -- SSD, HDD, NVMe
    capacity_gb   INT,
    read_mbs      INT,
    write_mbs     INT,
    price         NUMERIC(10,2)
);

INSERT INTO storage (name, brand, type, capacity_gb, read_mbs, write_mbs, price) VALUES
('990 Pro 1TB NVMe',          'Samsung', 'NVMe', 1000, 7450, 6900,  99.99),
('990 Pro 2TB NVMe',          'Samsung', 'NVMe', 2000, 7450, 6900, 179.99),
('SN850X 1TB NVMe',           'WD',      'NVMe', 1000, 7300, 6600,  89.99),
('SN850X 2TB NVMe',           'WD',      'NVMe', 2000, 7300, 6600, 169.99),
('870 EVO 1TB SATA SSD',      'Samsung', 'SSD',  1000,  560,  530,  79.99),
('870 EVO 500GB SATA SSD',    'Samsung', 'SSD',   500,  560,  530,  49.99),
('Barracuda 2TB HDD 7200rpm', 'Seagate', 'HDD',  2000,  220,  220,  49.99),
('Barracuda 4TB HDD 7200rpm', 'Seagate', 'HDD',  4000,  220,  220,  79.99);


-- ─── FUENTES DE ALIMENTACIÓN ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS psus (
    id              SERIAL PRIMARY KEY,
    name            VARCHAR(200) NOT NULL,
    brand           VARCHAR(100),
    watts           INT,
    certification   VARCHAR(50),   -- 80Plus Bronze, Gold, Platinum, Titanium
    modular         BOOLEAN,
    price           NUMERIC(10,2)
);

INSERT INTO psus (name, brand, watts, certification, modular, price) VALUES
('RM1000x 1000W',          'Corsair', 1000, '80Plus Gold',     TRUE,  169.99),
('RM850x 850W',            'Corsair',  850, '80Plus Gold',     TRUE,  139.99),
('RM750 750W',             'Corsair',  750, '80Plus Gold',     TRUE,  109.99),
('Supernova G6 650W',      'EVGA',     650, '80Plus Gold',     TRUE,   89.99),
('Focus GX-850 850W',      'Seasonic', 850, '80Plus Gold',     TRUE,  149.99),
('Focus PX-750 750W',      'Seasonic', 750, '80Plus Platinum', TRUE,  129.99),
('MPG A850G 850W',         'MSI',      850, '80Plus Gold',     TRUE,  139.99),
('CV550 550W',             'Corsair',  550, '80Plus Bronze',   FALSE,  59.99);


-- ─── CAJAS ───────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS cases (
    id                       SERIAL PRIMARY KEY,
    name                     VARCHAR(200) NOT NULL,
    brand                    VARCHAR(100),
    supported_form_factors   VARCHAR(100),  -- "ATX,mATX,ITX"
    has_window               BOOLEAN,
    price                    NUMERIC(10,2)
);

INSERT INTO cases (name, brand, supported_form_factors, has_window, price) VALUES
('4000D Airflow',         'Corsair',     'ATX,mATX,ITX', TRUE,   94.99),
('5000D Airflow',         'Corsair',     'ATX,mATX,ITX', TRUE,  154.99),
('H510',                  'NZXT',        'ATX,mATX',     TRUE,   69.99),
('H7 Flow',               'NZXT',        'ATX,mATX,ITX', TRUE,  109.99),
('Meshify 2',             'Fractal',     'ATX,mATX,ITX', TRUE,  129.99),
('Define 7',              'Fractal',     'ATX,mATX,ITX', TRUE,  159.99),
('MAG Forge 320R Airflow','MSI',         'ATX,mATX',     TRUE,   79.99),
('Pure Base 500DX',       'Be Quiet',    'ATX,mATX,ITX', TRUE,  109.99);


-- ─── REFRIGERACIÓN ───────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS coolers (
    id                   SERIAL PRIMARY KEY,
    name                 VARCHAR(200) NOT NULL,
    brand                VARCHAR(100),
    type                 VARCHAR(50),   -- Air, AIO 240, AIO 280, AIO 360
    compatible_sockets   TEXT,          -- "AM5,AM4,LGA1700,LGA1200"
    tdp_w                INT,
    price                NUMERIC(10,2)
);

INSERT INTO coolers (name, brand, type, compatible_sockets, tdp_w, price) VALUES
('NH-D15',               'Noctua',      'Air',     'AM5,AM4,LGA1700,LGA1200', 250,  99.99),
('NH-U12S redux',        'Noctua',      'Air',     'AM5,AM4,LGA1700,LGA1200', 150,  49.99),
('Dark Rock Pro 4',      'Be Quiet',    'Air',     'AM5,AM4,LGA1700,LGA1200', 250,  89.99),
('Hyper 212 Black',      'Cooler Master','Air',    'AM5,AM4,LGA1700,LGA1200', 150,  39.99),
('Kraken X63 280mm AIO', 'NZXT',        'AIO 280', 'AM5,AM4,LGA1700,LGA1200', 300, 149.99),
('Kraken X73 360mm AIO', 'NZXT',        'AIO 360', 'AM5,AM4,LGA1700,LGA1200', 350, 179.99),
('H150i Elite 360mm AIO','Corsair',     'AIO 360', 'AM5,AM4,LGA1700,LGA1200', 350, 189.99),
('H115i Elite 280mm AIO','Corsair',     'AIO 280', 'AM5,AM4,LGA1700,LGA1200', 300, 159.99);


-- ─── PERIFÉRICOS ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS peripherals (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(200) NOT NULL,
    brand         VARCHAR(100),
    type          VARCHAR(50),   -- teclado, raton, monitor, auriculares, webcam
    price         NUMERIC(10,2)
);

INSERT INTO peripherals (name, brand, type, price) VALUES
('G Pro X TKL Teclado',     'Logitech', 'teclado',     129.99),
('K70 RGB MK.2 Teclado',    'Corsair',  'teclado',     139.99),
('Anne Pro 2 Teclado',      'Obins',    'teclado',      89.99),
('G502 X Plus Ratón',       'Logitech', 'raton',        99.99),
('DeathAdder V3 Ratón',     'Razer',    'raton',        69.99),
('G Pro X Superlight Ratón','Logitech', 'raton',       149.99),
('27GP850-B 27" 165Hz',     'LG',       'monitor',     299.99),
('ODYSSEY G5 27" 144Hz',    'Samsung',  'monitor',     249.99),
('2725DP 27" 180Hz',        'Dell',     'monitor',     349.99),
('Cloud II Auriculares',    'HyperX',   'auriculares',  79.99),
('BlackShark V2 Auriculares','Razer',   'auriculares',  99.99),
('C920 Webcam HD',          'Logitech', 'webcam',       79.99);
