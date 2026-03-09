"""
PC Builder API — FastAPI + PostgreSQL
pip install fastapi uvicorn psycopg2-binary python-dotenv
uvicorn backend_api:app --host 127.0.0.1 --port 8000
"""

import os
from contextlib import contextmanager
from typing import Optional

import psycopg2
import psycopg2.extras
from fastapi import FastAPI, Query, HTTPException
from fastapi.middleware.cors import CORSMiddleware

try:
    from dotenv import load_dotenv
    load_dotenv()
except ImportError:
    pass

app = FastAPI(title="PC Builder API", version="3.0.0")
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

DB_CONFIG = {
    "host":     os.getenv("DB_HOST",     "aws-1-eu-west-1.pooler.supabase.com"),
    "port":     int(os.getenv("DB_PORT", 6543)),
    "user":     os.getenv("DB_USER",     "postgres.hoxlygwdxsdmzhmcdolr"),
    "password": os.getenv("DB_PASSWORD", "123456"),
    "dbname":   os.getenv("DB_NAME",     "postgres"),
}


@contextmanager
def get_conn():
    conn = None
    try:
        conn = psycopg2.connect(**DB_CONFIG)
        yield conn
    except psycopg2.OperationalError as e:
        raise HTTPException(status_code=500, detail=f"Error de conexion DB: {e}")
    finally:
        if conn:
            conn.close()


def run_query(sql: str, params: tuple = ()) -> list:
    with get_conn() as conn:
        with conn.cursor(cursor_factory=psycopg2.extras.RealDictCursor) as cur:
            cur.execute(sql, params)
            return [dict(row) for row in cur.fetchall()]


def get_motherboard(mb_id: int) -> dict:
    rows = run_query("SELECT * FROM motherboards WHERE id = %s", (mb_id,))
    if not rows:
        raise HTTPException(404, "Placa base no encontrada")
    return rows[0]


# ─── HEALTH ──────────────────────────────────────────────────────────────────
@app.get("/api/health")
def health():
    try:
        run_query("SELECT 1")
        return {"status": "ok", "db": "PostgreSQL conectada correctamente"}
    except Exception as e:
        return {"status": "error", "detail": str(e)}


# ─── PLACAS BASE ─────────────────────────────────────────────────────────────
@app.get("/api/motherboards")
def get_motherboards(
    search:      Optional[str]   = Query(None),
    socket:      Optional[str]   = Query(None),
    brand:       Optional[str]   = Query(None),
    max_price:   Optional[float] = Query(None),
    form_factor: Optional[str]   = Query(None),
    gama:        Optional[str]   = Query(None),
    cpu_id:      Optional[int]   = Query(None),
):
    sql    = "SELECT * FROM motherboards WHERE TRUE"
    params = []
    # Si se pasa cpu_id, filtrar por socket compatible con ese CPU
    if cpu_id:
        cpu_rows = run_query("SELECT socket FROM cpus WHERE id = %s", (cpu_id,))
        if cpu_rows:
            sql += " AND socket = %s"
            params.append(cpu_rows[0]["socket"])
    if search:
        sql += " AND (name ILIKE %s OR brand ILIKE %s)"
        params += [f"%{search}%", f"%{search}%"]
    if socket:
        sql += " AND socket = %s"
        params.append(socket)
    if brand:
        sql += " AND brand ILIKE %s"
        params.append(f"%{brand}%")
    if max_price is not None:
        sql += " AND price <= %s"
        params.append(max_price)
    if form_factor:
        sql += " AND form_factor = %s"
        params.append(form_factor)
    if gama:
        sql += " AND gama = %s"
        params.append(gama)
    sql += " ORDER BY price ASC LIMIT 60"
    return run_query(sql, tuple(params))


# ─── CPUs ─────────────────────────────────────────────────────────────────────
@app.get("/api/cpus")
def get_cpus(
    motherboard_id: Optional[int]   = Query(None),
    search:         Optional[str]   = Query(None),
    brand:          Optional[str]   = Query(None),
    max_price:      Optional[float] = Query(None),
    min_cores:      Optional[int]   = Query(None),
    gama:           Optional[str]   = Query(None),
):
    params = []
    if motherboard_id:
        mb  = get_motherboard(motherboard_id)
        sql = "SELECT * FROM cpus WHERE socket = %s"
        params.append(mb["socket"])
    else:
        sql = "SELECT * FROM cpus WHERE TRUE"
    if search:
        sql += " AND (name ILIKE %s OR brand ILIKE %s)"
        params += [f"%{search}%", f"%{search}%"]
    if brand:
        sql += " AND brand ILIKE %s"
        params.append(f"%{brand}%")
    if max_price is not None:
        sql += " AND price <= %s"
        params.append(max_price)
    if min_cores is not None:
        sql += " AND cores >= %s"
        params.append(min_cores)
    if gama:
        sql += " AND gama = %s"
        params.append(gama)
    sql += " ORDER BY price ASC LIMIT 60"
    return run_query(sql, tuple(params))


# ─── RAM ──────────────────────────────────────────────────────────────────────
@app.get("/api/ram")
def get_ram(
    motherboard_id: Optional[int]   = Query(None),
    search:         Optional[str]   = Query(None),
    brand:          Optional[str]   = Query(None),
    max_price:      Optional[float] = Query(None),
    min_gb:         Optional[int]   = Query(None),
    gama:           Optional[str]   = Query(None),
):
    params = []
    if motherboard_id:
        mb  = get_motherboard(motherboard_id)
        sql = "SELECT * FROM ram WHERE type = %s"
        params.append(mb["memory_type"])
        if mb.get("max_memory_gb"):
            sql += " AND capacity_gb <= %s"
            params.append(mb["max_memory_gb"])
    else:
        sql = "SELECT * FROM ram WHERE TRUE"
    if search:
        sql += " AND (name ILIKE %s OR brand ILIKE %s)"
        params += [f"%{search}%", f"%{search}%"]
    if brand:
        sql += " AND brand ILIKE %s"
        params.append(f"%{brand}%")
    if max_price is not None:
        sql += " AND price <= %s"
        params.append(max_price)
    if min_gb is not None:
        sql += " AND capacity_gb >= %s"
        params.append(min_gb)
    if gama:
        sql += " AND gama = %s"
        params.append(gama)
    sql += " ORDER BY price ASC LIMIT 60"
    return run_query(sql, tuple(params))


# ─── GPUs ─────────────────────────────────────────────────────────────────────
@app.get("/api/gpus")
def get_gpus(
    motherboard_id: Optional[int]   = Query(None),
    search:         Optional[str]   = Query(None),
    brand:          Optional[str]   = Query(None),
    max_price:      Optional[float] = Query(None),
    min_vram:       Optional[int]   = Query(None),
    gama:           Optional[str]   = Query(None),
):
    params = []
    sql = "SELECT * FROM gpus WHERE TRUE"
    if motherboard_id:
        mb       = get_motherboard(motherboard_id)
        mb_pcie  = mb.get("pcie_version", "PCIe 4.0")
        pcie_num = int(mb_pcie.replace("PCIe ", "").split(".")[0]) if mb_pcie else 4
        accepted = [f"PCIe {v}.0" for v in range(1, pcie_num + 1)]
        placeholders = ",".join(["%s"] * len(accepted))
        sql += f" AND pcie_version IN ({placeholders})"
        params += accepted
    if search:
        sql += " AND (name ILIKE %s OR brand ILIKE %s)"
        params += [f"%{search}%", f"%{search}%"]
    if brand:
        sql += " AND brand ILIKE %s"
        params.append(f"%{brand}%")
    if max_price is not None:
        sql += " AND price <= %s"
        params.append(max_price)
    if min_vram is not None:
        sql += " AND vram_gb >= %s"
        params.append(min_vram)
    if gama:
        sql += " AND gama = %s"
        params.append(gama)
    sql += " ORDER BY price ASC LIMIT 60"
    return run_query(sql, tuple(params))


# ─── ALMACENAMIENTO ───────────────────────────────────────────────────────────
@app.get("/api/storage")
def get_storage(
    motherboard_id: Optional[int]   = Query(None),
    search:         Optional[str]   = Query(None),
    type:           Optional[str]   = Query(None),
    brand:          Optional[str]   = Query(None),
    max_price:      Optional[float] = Query(None),
    min_gb:         Optional[int]   = Query(None),
    gama:           Optional[str]   = Query(None),
):
    params = []
    sql = "SELECT * FROM storage WHERE TRUE"
    if motherboard_id:
        mb = get_motherboard(motherboard_id)
        allowed = []
        if mb.get("m2_pcie"):
            allowed += ["NVMe-PCIe4", "NVMe-PCIe3"]
        if mb.get("m2_sata") or mb.get("sata_ports", 0) > 0:
            allowed += ["SATA", "HDD-SATA"]
        if allowed:
            placeholders = ",".join(["%s"] * len(allowed))
            sql += f" AND interface IN ({placeholders})"
            params += allowed
    if search:
        sql += " AND (name ILIKE %s OR brand ILIKE %s)"
        params += [f"%{search}%", f"%{search}%"]
    if type:
        sql += " AND type ILIKE %s"
        params.append(f"%{type}%")
    if brand:
        sql += " AND brand ILIKE %s"
        params.append(f"%{brand}%")
    if max_price is not None:
        sql += " AND price <= %s"
        params.append(max_price)
    if min_gb is not None:
        sql += " AND capacity_gb >= %s"
        params.append(min_gb)
    if gama:
        sql += " AND gama = %s"
        params.append(gama)
    sql += " ORDER BY price ASC LIMIT 60"
    return run_query(sql, tuple(params))


# ─── FUENTES ──────────────────────────────────────────────────────────────────
@app.get("/api/psus")
def get_psus(
    gpu_id:        Optional[int]   = Query(None),
    search:        Optional[str]   = Query(None),
    brand:         Optional[str]   = Query(None),
    max_price:     Optional[float] = Query(None),
    min_watts:     Optional[int]   = Query(None),
    certification: Optional[str]   = Query(None),
    gama:          Optional[str]   = Query(None),
):
    params = []
    sql = "SELECT * FROM psus WHERE TRUE"
    if gpu_id:
        gpu = run_query("SELECT tdp_w FROM gpus WHERE id = %s", (gpu_id,))
        if gpu:
            min_w = (gpu[0]["tdp_w"] or 0) + 200
            sql += " AND watts >= %s"
            params.append(min_w)
    if search:
        sql += " AND (name ILIKE %s OR brand ILIKE %s)"
        params += [f"%{search}%", f"%{search}%"]
    if brand:
        sql += " AND brand ILIKE %s"
        params.append(f"%{brand}%")
    if max_price is not None:
        sql += " AND price <= %s"
        params.append(max_price)
    if min_watts is not None:
        sql += " AND watts >= %s"
        params.append(min_watts)
    if certification:
        sql += " AND certification ILIKE %s"
        params.append(f"%{certification}%")
    if gama:
        sql += " AND gama = %s"
        params.append(gama)
    sql += " ORDER BY price ASC LIMIT 60"
    return run_query(sql, tuple(params))


# ─── CAJAS ────────────────────────────────────────────────────────────────────
@app.get("/api/cases")
def get_cases(
    motherboard_id: Optional[int]   = Query(None),
    gpu_id:         Optional[int]   = Query(None),
    cooler_id:      Optional[int]   = Query(None),
    search:         Optional[str]   = Query(None),
    brand:          Optional[str]   = Query(None),
    max_price:      Optional[float] = Query(None),
    gama:           Optional[str]   = Query(None),
):
    params = []
    sql = "SELECT * FROM cases WHERE TRUE"
    if motherboard_id:
        mb = get_motherboard(motherboard_id)
        ff = mb.get("form_factor", "ATX")
        sql += " AND supported_form_factors ILIKE %s"
        params.append(f"%{ff}%")
    if gpu_id:
        gpu = run_query("SELECT tdp_w FROM gpus WHERE id = %s", (gpu_id,))
        if gpu:
            tdp = gpu[0].get("tdp_w", 200)
            est_length = 330 if tdp > 300 else (280 if tdp > 200 else 240)
            sql += " AND max_gpu_mm >= %s"
            params.append(est_length)
    if cooler_id:
        cooler = run_query("SELECT height_mm, radiator_mm FROM coolers WHERE id = %s", (cooler_id,))
        if cooler:
            h = cooler[0].get("height_mm", 0)
            r = cooler[0].get("radiator_mm", 0)
            if h and h > 0:
                sql += " AND max_cooler_mm >= %s"
                params.append(h)
            if r and r > 0:
                sql += " AND radiator_support ILIKE %s"
                params.append(f"%{r}%")
    if search:
        sql += " AND (name ILIKE %s OR brand ILIKE %s)"
        params += [f"%{search}%", f"%{search}%"]
    if brand:
        sql += " AND brand ILIKE %s"
        params.append(f"%{brand}%")
    if max_price is not None:
        sql += " AND price <= %s"
        params.append(max_price)
    if gama:
        sql += " AND gama = %s"
        params.append(gama)
    sql += " ORDER BY price ASC LIMIT 60"
    return run_query(sql, tuple(params))


# ─── REFRIGERACION ────────────────────────────────────────────────────────────
@app.get("/api/coolers")
def get_coolers(
    motherboard_id: Optional[int]   = Query(None),
    case_id:        Optional[int]   = Query(None),
    search:         Optional[str]   = Query(None),
    brand:          Optional[str]   = Query(None),
    max_price:      Optional[float] = Query(None),
    type:           Optional[str]   = Query(None),
    gama:           Optional[str]   = Query(None),
):
    params = []
    if motherboard_id:
        mb  = get_motherboard(motherboard_id)
        sql = "SELECT * FROM coolers WHERE compatible_sockets ILIKE %s"
        params.append(f"%{mb['socket']}%")
    else:
        sql = "SELECT * FROM coolers WHERE TRUE"
    if case_id:
        case = run_query("SELECT max_cooler_mm, radiator_support FROM cases WHERE id = %s", (case_id,))
        if case:
            max_h = case[0].get("max_cooler_mm", 999)
            rad   = case[0].get("radiator_support", "")
            sub_params = [max_h]
            sub_sql = " AND ((height_mm > 0 AND height_mm <= %s)"
            if rad:
                rad_sizes = [r.strip() for r in rad.split(",")]
                rad_checks2 = " OR ".join(["radiator_mm = %s" for _ in rad_sizes])
                for rs in rad_sizes:
                    sub_params.append(int(rs))
                sub_sql += f" OR (radiator_mm > 0 AND ({rad_checks2})))"
            else:
                sub_sql += ")"
            sql += sub_sql
            params += sub_params
    if search:
        sql += " AND (name ILIKE %s OR brand ILIKE %s)"
        params += [f"%{search}%", f"%{search}%"]
    if brand:
        sql += " AND brand ILIKE %s"
        params.append(f"%{brand}%")
    if max_price is not None:
        sql += " AND price <= %s"
        params.append(max_price)
    if type:
        sql += " AND type ILIKE %s"
        params.append(f"%{type}%")
    if gama:
        sql += " AND gama = %s"
        params.append(gama)
    sql += " ORDER BY price ASC LIMIT 60"
    return run_query(sql, tuple(params))


# ─── MONITORES ────────────────────────────────────────────────────────────────
@app.get("/api/monitors")
def get_monitors(
    search:     Optional[str]   = Query(None),
    brand:      Optional[str]   = Query(None),
    resolution: Optional[str]   = Query(None),
    min_hz:     Optional[int]   = Query(None),
    panel_type: Optional[str]   = Query(None),
    max_price:  Optional[float] = Query(None),
    hdr:        Optional[bool]  = Query(None),
    gama:       Optional[str]   = Query(None),
):
    sql    = "SELECT * FROM monitors WHERE TRUE"
    params = []
    if search:
        sql += " AND (name ILIKE %s OR brand ILIKE %s)"
        params += [f"%{search}%", f"%{search}%"]
    if brand:
        sql += " AND brand ILIKE %s"
        params.append(f"%{brand}%")
    if resolution:
        sql += " AND resolution ILIKE %s"
        params.append(f"%{resolution}%")
    if min_hz is not None:
        sql += " AND refresh_hz >= %s"
        params.append(min_hz)
    if panel_type:
        sql += " AND panel_type ILIKE %s"
        params.append(f"%{panel_type}%")
    if max_price is not None:
        sql += " AND price <= %s"
        params.append(max_price)
    if hdr is not None:
        sql += " AND hdr = %s"
        params.append(hdr)
    if gama:
        sql += " AND gama = %s"
        params.append(gama)
    sql += " ORDER BY price ASC LIMIT 60"
    return run_query(sql, tuple(params))


# ─── TECLADOS ─────────────────────────────────────────────────────────────────
@app.get("/api/keyboards")
def get_keyboards(
    search:    Optional[str]   = Query(None),
    brand:     Optional[str]   = Query(None),
    wireless:  Optional[bool]  = Query(None),
    layout:    Optional[str]   = Query(None),
    max_price: Optional[float] = Query(None),
    gama:      Optional[str]   = Query(None),
):
    sql    = "SELECT * FROM keyboards WHERE TRUE"
    params = []
    if search:
        sql += " AND (name ILIKE %s OR brand ILIKE %s)"
        params += [f"%{search}%", f"%{search}%"]
    if brand:
        sql += " AND brand ILIKE %s"
        params.append(f"%{brand}%")
    if wireless is not None:
        sql += " AND wireless = %s"
        params.append(wireless)
    if layout:
        sql += " AND layout ILIKE %s"
        params.append(f"%{layout}%")
    if max_price is not None:
        sql += " AND price <= %s"
        params.append(max_price)
    if gama:
        sql += " AND gama = %s"
        params.append(gama)
    sql += " ORDER BY price ASC LIMIT 60"
    return run_query(sql, tuple(params))


# ─── RATONES ──────────────────────────────────────────────────────────────────
@app.get("/api/mice")
def get_mice(
    search:    Optional[str]   = Query(None),
    brand:     Optional[str]   = Query(None),
    wireless:  Optional[bool]  = Query(None),
    min_dpi:   Optional[int]   = Query(None),
    max_price: Optional[float] = Query(None),
    gama:      Optional[str]   = Query(None),
):
    sql    = "SELECT * FROM mice WHERE TRUE"
    params = []
    if search:
        sql += " AND (name ILIKE %s OR brand ILIKE %s)"
        params += [f"%{search}%", f"%{search}%"]
    if brand:
        sql += " AND brand ILIKE %s"
        params.append(f"%{brand}%")
    if wireless is not None:
        sql += " AND wireless = %s"
        params.append(wireless)
    if min_dpi is not None:
        sql += " AND dpi_max >= %s"
        params.append(min_dpi)
    if max_price is not None:
        sql += " AND price <= %s"
        params.append(max_price)
    if gama:
        sql += " AND gama = %s"
        params.append(gama)
    sql += " ORDER BY price ASC LIMIT 60"
    return run_query(sql, tuple(params))


# ─── SUGERENCIAS ──────────────────────────────────────────────────────────────
@app.get("/api/suggest/{component_type}/{component_id}")
def suggest_upgrade(
    component_type: str,
    component_id:   int,
    motherboard_id: Optional[int] = Query(None),
    gpu_id:         Optional[int] = Query(None),
    cooler_id:      Optional[int] = Query(None),
    case_id:        Optional[int] = Query(None),
):
    table_map = {
        "motherboard": "motherboards",
        "cpu":         "cpus",
        "ram":         "ram",
        "gpu":         "gpus",
        "storage":     "storage",
        "psu":         "psus",
        "case":        "cases",
        "cooler":      "coolers",
        "monitor":     "monitors",
        "keyboard":    "keyboards",
        "mouse":       "mice",
    }
    table = table_map.get(component_type)
    if not table:
        raise HTTPException(400, f"Tipo desconocido: {component_type}")

    current = run_query(f"SELECT * FROM {table} WHERE id = %s", (component_id,))
    if not current:
        raise HTTPException(404, "Componente no encontrado")

    price  = current[0].get("price", 0)
    params: list = [price]
    sql    = f"SELECT * FROM {table} WHERE price > %s"

    if motherboard_id:
        mb = get_motherboard(motherboard_id)
        if component_type == "cpu":
            sql += " AND socket = %s"
            params.append(mb["socket"])
        elif component_type == "ram":
            sql += " AND type = %s"
            params.append(mb["memory_type"])
            if mb.get("max_memory_gb"):
                sql += " AND capacity_gb <= %s"
                params.append(mb["max_memory_gb"])
        elif component_type == "gpu":
            mb_pcie  = mb.get("pcie_version", "PCIe 4.0")
            pcie_num = int(mb_pcie.replace("PCIe ", "").split(".")[0]) if mb_pcie else 4
            accepted = [f"PCIe {v}.0" for v in range(1, pcie_num + 1)]
            placeholders = ",".join(["%s"] * len(accepted))
            sql += f" AND pcie_version IN ({placeholders})"
            params += accepted
        elif component_type == "storage":
            allowed = []
            if mb.get("m2_pcie"):
                allowed += ["NVMe-PCIe4", "NVMe-PCIe3"]
            if mb.get("m2_sata") or mb.get("sata_ports", 0) > 0:
                allowed += ["SATA", "HDD-SATA"]
            if allowed:
                placeholders = ",".join(["%s"] * len(allowed))
                sql += f" AND interface IN ({placeholders})"
                params += allowed
        elif component_type == "cooler":
            sql += " AND compatible_sockets ILIKE %s"
            params.append(f"%{mb['socket']}%")

    if gpu_id and component_type == "psu":
        gpu = run_query("SELECT tdp_w FROM gpus WHERE id = %s", (gpu_id,))
        if gpu:
            sql += " AND watts >= %s"
            params.append((gpu[0]["tdp_w"] or 0) + 200)

    if component_type == "case":
        if motherboard_id:
            mb = get_motherboard(motherboard_id)
            sql += " AND supported_form_factors ILIKE %s"
            params.append(f"%{mb.get('form_factor','ATX')}%")
        if cooler_id:
            cooler = run_query("SELECT height_mm, radiator_mm FROM coolers WHERE id = %s", (cooler_id,))
            if cooler:
                h = cooler[0].get("height_mm", 0)
                r = cooler[0].get("radiator_mm", 0)
                if h:
                    sql += " AND max_cooler_mm >= %s"
                    params.append(h)
                if r:
                    sql += " AND radiator_support ILIKE %s"
                    params.append(f"%{r}%")

    sql += " ORDER BY price ASC LIMIT 3"
    suggestions = run_query(sql, tuple(params))
    return {"current": current[0], "suggestions": suggestions}


# ─── INFO COMPATIBILIDAD ──────────────────────────────────────────────────────
@app.get("/api/compat-info/{motherboard_id}")
def compat_info(motherboard_id: int):
    mb = get_motherboard(motherboard_id)
    return {
        "socket":        mb.get("socket"),
        "memory_type":   mb.get("memory_type"),
        "pcie_version":  mb.get("pcie_version"),
        "m2_slots":      mb.get("m2_slots", 0),
        "m2_nvme":       mb.get("m2_pcie", False),
        "m2_sata":       mb.get("m2_sata", False),
        "sata_ports":    mb.get("sata_ports", 0),
        "max_memory_gb": mb.get("max_memory_gb", 128),
        "form_factor":   mb.get("form_factor"),
        "storage_accepts": (
            ["NVMe M.2"] * bool(mb.get("m2_pcie")) +
            ["SATA M.2"] * bool(mb.get("m2_sata")) +
            [f"{mb.get('sata_ports',0)} puertos SATA"] * bool(mb.get("sata_ports"))
        ),
    }
