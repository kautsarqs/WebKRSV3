# Arsitektur Sistem WebKRSV3 — Diagram Per Fase

> Setiap fase menunjukkan satu lapisan interaksi dalam arsitektur 3-tier.
> **→ Solid** = Request/Forward | **⇢ Dashed** = Response/Return

---

## Fase A — Entry Point (Presentation → Router & External)

> Titik masuk request dari sisi klien menuju server dan layanan eksternal.

```mermaid
flowchart TD
    classDef pres fill:#e8f0fe,stroke:#1a73e8,stroke-width:2px,color:#174ea6
    classDef app fill:#e6f4ea,stroke:#137333,stroke-width:2px,color:#137333
    classDef ext fill:#fce8e6,stroke:#c5221f,stroke-width:2px,color:#c5221f

    subgraph PRESENTATION["🖥️ Presentation Tier"]
        direction LR
        UP["<b>User Portal UI — Client PWA</b>
        • Laravel Blade Templates
        • Tailwind CSS v4 & Alpine.js
        • Leaflet.js Map Viewer
        • sw.js & manifest.json"]

        AD["<b>Admin Dashboard UI</b>
        • Admin Blade Templates
        • Admin Control Panel
        • GeoJSON Boundary Editor
        • Data Export CSV/Excel"]
    end

    subgraph APPLICATION["⚙️ Application Tier"]
        RT["<b>Web Router & Middleware</b>
        routes/web.php
        • Route Handler
        • Middleware: auth, verified, admin"]
    end

    OSM(["🗺️ <b>OpenStreetMap</b>
    Basemap Tiles"])

    UP -->|"1a. HTTP Request
    (Kunjungan, Riset)"| RT
    AD -->|"1b. HTTP Request
    (CRUD, Persetujuan)"| RT
    UP -->|"1c. Load Basemap Tiles"| OSM
    OSM -.->|"1c. Return Tile Images"| UP

    class UP,AD pres
    class RT app
    class OSM ext
```

---

## Fase B — Routing & Middleware (Router → Controllers)

> Router memetakan HTTP request ke controller yang sesuai melalui middleware keamanan.

```mermaid
flowchart TD
    classDef app fill:#e6f4ea,stroke:#137333,stroke-width:2px,color:#137333

    RT["<b>Web Router & Middleware</b>
    routes/web.php
    • Route Handler
    • Middleware: auth, verified, admin"]

    AUTH["<b>Auth & Profile Controllers</b>
    • AuthController.php — Login/Register
    • SocialiteController.php — Google OAuth
    • ProfileController.php — Manajemen Akun"]

    MAP["<b>Koleksi & Map Controllers</b>
    • KoleksiController.php — Katalog Flora
    • Admin/MapController.php — GeoJSON Marker
    • Admin/UserController.php — Manajemen User"]

    PEND["<b>Pendaftaran Controllers</b>
    • PendaftaranController.php — Kunjungan
    • Admin/PendaftaranManageController.php
    • Logika Persetujuan Izin Riset"]

    RT -->|"2a. Route &
    Auth Middleware"| AUTH
    RT -->|"2b. Route &
    Admin Middleware"| MAP
    RT -->|"2c. Route &
    Verified Middleware"| PEND

    class RT,AUTH,MAP,PEND app
```

---

## Fase C — Side Effects (Controllers → External Services & Helpers)

> Controller memanggil layanan eksternal dan helper internal untuk proses tambahan.

```mermaid
flowchart LR
    classDef app fill:#e6f4ea,stroke:#137333,stroke-width:2px,color:#137333
    classDef ext fill:#fce8e6,stroke:#c5221f,stroke-width:2px,color:#c5221f

    AUTH["<b>Auth & Profile
    Controllers</b>"]

    MAP["<b>Koleksi & Map
    Controllers</b>"]

    PEND["<b>Pendaftaran
    Controllers</b>"]

    SVC["<b>Application Services & Helpers</b>
    • ImageOptimizer — Kompresi & Konversi AVIF
    • PendaftaranPenelitiMail — Mailer SMTP"]

    GOOGLE(["🔐 <b>Google OAuth API</b>
    Laravel Socialite"])

    AUTH -->|"3a. Google Socialite Auth"| GOOGLE
    GOOGLE -.->|"3a. Return OAuth User Data"| AUTH

    MAP -->|"3b. Optimasi AVIF"| SVC
    SVC -.->|"3b. Return AVIF Path"| MAP

    PEND -->|"3c. Notifikasi Email"| SVC
    SVC -.->|"3c. Return Mail Status"| PEND

    class AUTH,MAP,PEND,SVC app
    class GOOGLE ext
```

---

## Fase D — Data Query & External Relay (Controllers/Services → Models & SMTP)

> Controller dan Services melakukan query data via Eloquent ORM dan mengirim email via SMTP.

```mermaid
flowchart TD
    classDef app fill:#e6f4ea,stroke:#137333,stroke-width:2px,color:#137333
    classDef data fill:#fef7e0,stroke:#b06000,stroke-width:2px,color:#b06000
    classDef ext fill:#fce8e6,stroke:#c5221f,stroke-width:2px,color:#c5221f

    AUTH["<b>Auth & Profile
    Controllers</b>"]

    MAP["<b>Koleksi & Map
    Controllers</b>"]

    PEND["<b>Pendaftaran
    Controllers</b>"]

    SVC["<b>Application Services
    & Helpers</b>"]

    MODELS["<b>Eloquent ORM Models</b>
    User.php • Koleksi.php • MapMarker.php
    PendaftaranPengunjung.php • PendaftaranPeneliti.php"]

    SMTP(["📧 <b>SMTP Gmail Server</b>
    Notifikasi Admin"])

    SVC -->|"4a. SMTP Relay"| SMTP
    SMTP -.->|"4a. Return SMTP Response"| SVC

    AUTH -->|"4b. User Query"| MODELS
    MODELS -.->|"4b. Return User Data"| AUTH

    MAP -->|"4c. Spasial & Katalog Query"| MODELS
    MODELS -.->|"4c. Return Koleksi /
    MapMarker Data"| MAP

    PEND -->|"4d. Reservasi Query"| MODELS
    MODELS -.->|"4d. Return
    Pendaftaran Records"| PEND

    class AUTH,MAP,PEND,SVC app
    class MODELS data
    class SMTP ext
```

---

## Fase E — Database Access (Models ↔ PostgreSQL)

> Eloquent ORM mentranslasi operasi model menjadi SQL query ke database PostgreSQL.

```mermaid
flowchart TD
    classDef data fill:#fef7e0,stroke:#b06000,stroke-width:2px,color:#b06000
    classDef db fill:#fef7e0,stroke:#b06000,stroke-width:3px,color:#b06000

    MODELS["<b>Eloquent ORM Models — Data Access Layer</b>
    User.php • Koleksi.php • MapMarker.php
    PendaftaranPengunjung.php • PendaftaranPeneliti.php"]

    DB[("🐘 <b>PostgreSQL Database</b>
    ─────────────────────
    Penyimpanan Relasional & Spasial
    ─────────────────────
    <b>Tabel:</b>
    • users
    • koleksis
    • map_markers — GeoJSON
    • pendaftaran_pengunjungs
    • pendaftaran_penelitis
    • categories")]

    MODELS -->|"5a. SQL Query / DDL Execution"| DB
    DB -.->|"5b. Return ResultSet / Records"| MODELS

    class MODELS data
    class DB db
```

---

## Fase F — Response Return (Router → Presentation Tier)

> Router mengembalikan HTTP response berupa rendered HTML atau JSON ke sisi klien.

```mermaid
flowchart BT
    classDef pres fill:#e8f0fe,stroke:#1a73e8,stroke-width:2px,color:#174ea6
    classDef app fill:#e6f4ea,stroke:#137333,stroke-width:2px,color:#137333

    RT["<b>Web Router & Middleware</b>
    routes/web.php"]

    UP["<b>User Portal UI — Client PWA</b>
    Rendered Blade + Tailwind CSS
    Cached via sw.js"]

    AD["<b>Admin Dashboard UI</b>
    Rendered Admin Blade
    atau JSON Response"]

    RT -.->|"6a. Return HTML / Cache"| UP
    RT -.->|"6b. Return HTML / JSON Response"| AD

    class UP,AD pres
    class RT app
```

---

## Diagram Gabungan — Full Round-Trip

> Seluruh fase A–F digabung dalam satu diagram alur penuh.

```mermaid
flowchart TD
    classDef pres fill:#e8f0fe,stroke:#1a73e8,stroke-width:2px,color:#174ea6
    classDef app fill:#e6f4ea,stroke:#137333,stroke-width:2px,color:#137333
    classDef data fill:#fef7e0,stroke:#b06000,stroke-width:2px,color:#b06000
    classDef ext fill:#fce8e6,stroke:#c5221f,stroke-width:2px,color:#c5221f

    %% ── Presentation ──
    UP["User Portal UI"]
    AD["Admin Dashboard UI"]

    %% ── Application ──
    RT["Router & Middleware"]
    AUTH["Auth Controllers"]
    MAP["Map/Koleksi Controllers"]
    PEND["Pendaftaran Controllers"]
    SVC["Services & Helpers"]

    %% ── Data ──
    MDL["Eloquent Models"]
    DB[("PostgreSQL")]

    %% ── External ──
    OSM(["OpenStreetMap"])
    GOO(["Google OAuth"])
    SMTP(["SMTP Gmail"])

    %% ── Fase A: Entry ──
    UP -->|"1a"| RT
    AD -->|"1b"| RT
    UP -->|"1c"| OSM
    OSM -.->|"1c"| UP

    %% ── Fase B: Routing ──
    RT -->|"2a"| AUTH
    RT -->|"2b"| MAP
    RT -->|"2c"| PEND

    %% ── Fase C: Side Effects ──
    AUTH -->|"3a"| GOO
    GOO -.->|"3a"| AUTH
    MAP -->|"3b"| SVC
    SVC -.->|"3b"| MAP
    PEND -->|"3c"| SVC
    SVC -.->|"3c"| PEND

    %% ── Fase D: Data Query ──
    SVC -->|"4a"| SMTP
    SMTP -.->|"4a"| SVC
    AUTH -->|"4b"| MDL
    MDL -.->|"4b"| AUTH
    MAP -->|"4c"| MDL
    MDL -.->|"4c"| MAP
    PEND -->|"4d"| MDL
    MDL -.->|"4d"| PEND

    %% ── Fase E: Database ──
    MDL -->|"5a"| DB
    DB -.->|"5b"| MDL

    %% ── Fase F: Response ──
    RT -.->|"6a"| UP
    RT -.->|"6b"| AD

    class UP,AD pres
    class RT,AUTH,MAP,PEND,SVC app
    class MDL data
    class DB data
    class OSM,GOO,SMTP ext
```

---

## Ringkasan Fase

| Fase | Nama | Step | Deskripsi |
|:---:|:---|:---:|:---|
| **A** | Entry Point | 1a, 1b, 1c | Presentation Tier mengirim HTTP request ke Router dan memuat tile peta dari OSM |
| **B** | Routing & Middleware | 2a, 2b, 2c | Router memetakan request ke Controller melalui middleware keamanan |
| **C** | Side Effects | 3a, 3b, 3c | Controller memanggil layanan eksternal (Google OAuth) dan helper internal (AVIF, Mail) |
| **D** | Data Query & Relay | 4a, 4b, 4c, 4d | Controller query data via Eloquent ORM, Services relay email via SMTP |
| **E** | Database Access | 5a, 5b | Eloquent ORM mengeksekusi SQL ke PostgreSQL dan menerima ResultSet |
| **F** | Response Return | 6a, 6b | Router mengembalikan rendered HTML/JSON ke Presentation Tier |
