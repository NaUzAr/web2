-- =============================================
-- SQL: Create All Databases (3 Web)
-- =============================================
-- Jalankan ini di PostgreSQL saat setup pertama kali

-- Database 1: SmartAgri
CREATE DATABASE smartagri_db;
CREATE USER smartagri_user WITH ENCRYPTED PASSWORD 'smartagri_secret';
GRANT ALL PRIVILEGES ON DATABASE smartagri_db TO smartagri_user;

-- Database 2: Toko Online
CREATE DATABASE tokoonline_db;
CREATE USER tokoonline_user WITH ENCRYPTED PASSWORD 'tokoonline_secret';
GRANT ALL PRIVILEGES ON DATABASE tokoonline_db TO tokoonline_user;

-- Database 3: Portfolio
CREATE DATABASE portfolio_db;
CREATE USER portfolio_user WITH ENCRYPTED PASSWORD 'portfolio_secret';
GRANT ALL PRIVILEGES ON DATABASE portfolio_db TO portfolio_user;

-- Grant schema privileges (PostgreSQL 15+)
\c smartagri_db
GRANT ALL ON SCHEMA public TO smartagri_user;

\c tokoonline_db
GRANT ALL ON SCHEMA public TO tokoonline_user;

\c portfolio_db
GRANT ALL ON SCHEMA public TO portfolio_user;
