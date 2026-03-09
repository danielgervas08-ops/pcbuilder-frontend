-- ══════════════════════════════════════════════════════════════════════
--  NUEVAS TABLAS — Pantalla, Teclado, Ratón
--  Ejecutar: psql -h 192.168.5.66 -U web_user -d datastore_db -f peripherals_update.sql
-- ══════════════════════════════════════════════════════════════════════

-- Eliminar tabla periféricos genérica si existe
DROP TABLE IF EXISTS peripherals;

-- ─── PANTALLAS ───────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS monitors (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(200) NOT NULL,
    brand         VARCHAR(100),
    size_inch     NUMERIC(4,1),       -- 24.0, 27.0, 32.0
    resolution    VARCHAR(20),        -- 1080p, 1440p (2K), 4K
    refresh_hz    INT,                -- 60, 144, 165, 240
    panel_type    VARCHAR(10),        -- IPS, VA, TN, OLED
    response_ms   NUMERIC(4,1),       -- tiempo de respuesta en ms
    hdr           BOOLEAN DEFAULT FALSE,
    price         NUMERIC(10,2)
);

INSERT INTO monitors (name, brand, size_inch, resolution, refresh_hz, panel_type, response_ms, hdr, price) VALUES
('27GP850-B 27" 1440p 165Hz',     'LG',      27.0, '1440p', 165, 'IPS',  1.0, FALSE, 299.99),
('27GP950-B 27" 4K 160Hz',        'LG',      27.0, '4K',    160, 'NanoIPS', 1.0, TRUE,  699.99),
('24G2SP 24" 1080p 165Hz',        'AOC',     24.0, '1080p', 165, 'IPS',  1.0, FALSE, 149.99),
('27G2SP 27" 1080p 165Hz',        'AOC',     27.0, '1080p', 165, 'IPS',  1.0, FALSE, 179.99),
('ODYSSEY G5 27" 1440p 165Hz',    'Samsung', 27.0, '1440p', 165, 'VA',   1.0, FALSE, 249.99),
('ODYSSEY G7 32" 1440p 240Hz',    'Samsung', 32.0, '1440p', 240, 'VA',   1.0, TRUE,  499.99),
('2725DP 27" 1440p 180Hz',        'Dell',    27.0, '1440p', 180, 'IPS',  1.0, FALSE, 349.99),
('S3222DGM 32" 1440p 165Hz',      'Dell',    32.0, '1440p', 165, 'VA',   1.0, FALSE, 279.99),
('XB273U NX 27" 1440p 270Hz',     'Acer',    27.0, '1440p', 270, 'IPS',  0.5, FALSE, 399.99),
('EV2760 27" 1440p 60Hz',         'Eizo',    27.0, '1440p',  60, 'IPS',  5.0, FALSE, 549.99),
('X27 ARS 27" 4K 160Hz OLED',     'ASUS',    27.0, '4K',    160, 'OLED', 0.1, TRUE,  999.99),
('PG248QP 24" 1080p 540Hz',       'ASUS',    24.0, '1080p', 540, 'TN',   0.3, FALSE, 599.99);


-- ─── TECLADOS ────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS keyboards (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(200) NOT NULL,
    brand         VARCHAR(100),
    wireless      BOOLEAN DEFAULT FALSE,   -- TRUE = inalámbrico
    layout        VARCHAR(20),             -- Full, TKL, 75%, 65%, 60%
    switch_type   VARCHAR(50),             -- Cherry MX Red, Blue, Brown, etc.
    rgb           BOOLEAN DEFAULT FALSE,
    price         NUMERIC(10,2)
);

INSERT INTO keyboards (name, brand, wireless, layout, switch_type, rgb, price) VALUES
('G Pro X TKL',            'Logitech', FALSE, 'TKL',  'GX Blue',          TRUE,  129.99),
('G915 TKL',               'Logitech', TRUE,  'TKL',  'GL Tactile',        TRUE,  229.99),
('G915 Fullsize',          'Logitech', TRUE,  'Full', 'GL Linear',         TRUE,  249.99),
('K70 RGB MK.2',           'Corsair',  FALSE, 'Full', 'Cherry MX Red',     TRUE,  139.99),
('K65 RGB Mini',           'Corsair',  FALSE, '65%',  'Cherry MX Speed',   TRUE,   99.99),
('K100 RGB',               'Corsair',  FALSE, 'Full', 'Cherry MX Speed',   TRUE,  229.99),
('Huntsman V2 TKL',        'Razer',    FALSE, 'TKL',  'Razer Clicky Optical',TRUE, 159.99),
('BlackWidow V3 Pro',      'Razer',    TRUE,  'Full', 'Razer Yellow',      TRUE,  229.99),
('Anne Pro 2',             'Obins',    TRUE,  '60%',  'Gateron Brown',     TRUE,   89.99),
('Ducky One 3 TKL',        'Ducky',    FALSE, 'TKL',  'Cherry MX Brown',   TRUE,  119.99),
('Keychron K8 Pro',        'Keychron', TRUE,  'TKL',  'Gateron G Pro Red', TRUE,   99.99),
('Keychron Q1 Pro',        'Keychron', TRUE,  '75%',  'Gateron G Pro Brown',TRUE, 179.99);


-- ─── RATONES ─────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS mice (
    id            SERIAL PRIMARY KEY,
    name          VARCHAR(200) NOT NULL,
    brand         VARCHAR(100),
    wireless      BOOLEAN DEFAULT FALSE,   -- TRUE = inalámbrico
    dpi_max       INT,                     -- DPI máximo
    buttons       INT DEFAULT 6,           -- número de botones
    weight_g      INT,                     -- peso en gramos
    rgb           BOOLEAN DEFAULT FALSE,
    price         NUMERIC(10,2)
);

INSERT INTO mice (name, brand, wireless, dpi_max, buttons, weight_g, rgb, price) VALUES
('G502 X Plus',           'Logitech', TRUE,  25600, 13, 89,  TRUE,  149.99),
('G502 X',                'Logitech', FALSE, 25600, 13, 89,  TRUE,   79.99),
('G Pro X Superlight 2',  'Logitech', TRUE,  32000,  5, 60,  FALSE, 159.99),
('G305',                  'Logitech', TRUE,  12000,  6, 99,  FALSE,  49.99),
('DeathAdder V3',         'Razer',    FALSE, 30000,  8, 59,  FALSE,  69.99),
('DeathAdder V3 Pro',     'Razer',    TRUE,  30000,  8, 63,  FALSE, 149.99),
('Basilisk V3 Pro',       'Razer',    TRUE,  30000, 11, 112, TRUE,  159.99),
('Viper V3 Pro',          'Razer',    TRUE,  35000,  5, 54,  FALSE, 159.99),
('Rival 600',             'SteelSeries',FALSE,12000, 7, 96,  TRUE,   79.99),
('Aerox 5 Wireless',      'SteelSeries',TRUE, 18000, 9, 74,  TRUE,  139.99),
('Model O Wireless',      'Glorious',  TRUE, 19000,  6, 69,  TRUE,   79.99),
('Xlite V4',              'Pulsar',    FALSE,26000,  5, 52,  FALSE,  59.99);
