# =============================================
# CONTOH: 3 Web Apps dengan Perbedaannya
# =============================================
# 
# Web 1: SmartAgri (smartagri.web.id)
# Web 2: Toko Online (tokoonline.com)  
# Web 3: Portfolio (portfolio.me)
# =============================================

## Struktur Folder di Server:

```
/root/
├── smartagri-iot/           # Web 1
│   ├── docker-compose.app.yml
│   └── ...
│
├── tokoonline/              # Web 2
│   ├── docker-compose.app.yml
│   └── ...
│
├── portfolio/               # Web 3
│   ├── docker-compose.app.yml
│   └── ...
│
└── shared-services/         # Database & Nginx (shared)
    ├── docker-compose.db.yml
    └── docker-compose.nginx.yml
```

---

## Perbandingan 3 Web:

| Komponen | Web 1 (SmartAgri) | Web 2 (Toko) | Web 3 (Portfolio) |
|----------|-------------------|--------------|-------------------|
| **Domain** | smartagri.web.id | tokoonline.com | portfolio.me |
| **Folder** | /root/smartagri-iot | /root/tokoonline | /root/portfolio |
| **Container** | smartagri-app | tokoonline-app | portfolio-app |
| **Image** | smartagri-app | tokoonline-app | portfolio-app |
| **Database** | smartagri_db | tokoonline_db | portfolio_db |
| **DB User** | smartagri_user | tokoonline_user | portfolio_user |
| **PHP Port** | 9000 (internal) | 9000 (internal) | 9000 (internal) |

---

## File Lengkap Masing-masing:

Lihat file:
- `examples/web1-smartagri.yml`
- `examples/web2-tokoonline.yml`
- `examples/web3-portfolio.yml`
- `examples/nginx-multi-domain.conf`
- `examples/init-all-databases.sql`
