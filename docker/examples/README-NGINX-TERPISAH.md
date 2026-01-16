# =============================================
# PERBANDINGAN: Nginx Masing-masing vs Shared
# =============================================

## Port Mapping (Nginx Terpisah):

| Web | Container Nginx | HTTP Port | HTTPS Port | Akses |
|-----|-----------------|-----------|------------|-------|
| 1   | smartagri-nginx | 8080 | 8443 | http://IP:8080 |
| 2   | tokoonline-nginx | 8081 | 8444 | http://IP:8081 |
| 3   | portfolio-nginx | 8082 | 8445 | http://IP:8082 |

---

## Struktur Container:

```
┌─────────────────────────────────────────────────────────────┐
│                         SERVER                               │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌──────────────────┐  ┌──────────────────┐  ┌────────────┐ │
│  │ Web 1            │  │ Web 2            │  │ Web 3      │ │
│  │ ┌──────────────┐ │  │ ┌──────────────┐ │  │ ┌────────┐ │ │
│  │ │smartagri-    │ │  │ │tokoonline-   │ │  │ │portf-  │ │ │
│  │ │nginx :8080   │ │  │ │nginx :8081   │ │  │ │nginx   │ │ │
│  │ └──────┬───────┘ │  │ └──────┬───────┘ │  │ │:8082   │ │ │
│  │        │         │  │        │         │  │ └────┬───┘ │ │
│  │ ┌──────▼───────┐ │  │ ┌──────▼───────┐ │  │ ┌────▼───┐ │ │
│  │ │smartagri-app │ │  │ │tokoonline-   │ │  │ │portf-  │ │ │
│  │ │    :9000     │ │  │ │app :9000     │ │  │ │app     │ │ │
│  │ └──────────────┘ │  │ └──────────────┘ │  │ │:9000   │ │ │
│  └──────────────────┘  └──────────────────┘  │ └────────┘ │ │
│                                              └────────────┘ │
│  ┌──────────────────────────────────────────────────────┐   │
│  │              shared-db (PostgreSQL :5432)            │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

---

## Yang BEDA untuk tiap Web:

| Komponen | Web 1 | Web 2 | Web 3 |
|----------|-------|-------|-------|
| **Nginx container** | smartagri-nginx | tokoonline-nginx | portfolio-nginx |
| **HTTP Port** | 8080 | 8081 | 8082 |
| **HTTPS Port** | 8443 | 8444 | 8445 |
| **App container** | smartagri-app | tokoonline-app | portfolio-app |
| **Database** | smartagri_db | tokoonline_db | portfolio_db |
| **Volumes** | smartagri_* | tokoonline_* | portfolio_* |

---

## Akses:

- **Web 1:** http://smartagri.web.id:8080 atau http://IP:8080
- **Web 2:** http://tokoonline.com:8081 atau http://IP:8081  
- **Web 3:** http://portfolio.me:8082 atau http://IP:8082

---

## Kalau mau tanpa port (pakai domain langsung):

Perlu 1 **Reverse Proxy** di depan yang listen port 80/443:

```
Internet → [Reverse Proxy :80]
                  │
    ┌─────────────┼─────────────┐
    ▼             ▼             ▼
  :8080         :8081         :8082
(Web 1)        (Web 2)       (Web 3)
```

Bisa pakai **Traefik** atau **Nginx Proxy Manager** untuk ini.
