# Viata Luxe Guesthouse — cPanel Deployment Guide

## Prerequisites
- cPanel access (File Manager + MySQL Databases + phpMyAdmin)
- The deployment ZIP: `viata-luxe-deploy.zip` (47 MB)
- The SQL dump: `sql/viata-luxe-full.sql` (129 KB)

---

## Step 1: Create MySQL Database

1. cPanel → **MySQL Databases**
2. Create database: `viata_luxe` (full name may be `cpaneluser_viata_luxe`)
3. Create database user with a strong password
4. Add user to database with **ALL PRIVILEGES**
5. Note the full database name, username, and password

## Step 2: Import Database

1. cPanel → **phpMyAdmin**
2. Select the `viata_luxe` database
3. Click **Import** tab
4. Choose `sql/viata-luxe-full.sql`
5. Click **Go** — wait for import to complete
6. Verify 22 tables created

## Step 3: Upload Files

1. cPanel → **File Manager**
2. Navigate to `public_html` (or your subdomain folder)
3. Upload `viata-luxe-deploy.zip`
4. Extract the ZIP in `public_html`
5. Verify the folder structure:
   ```
   public_html/
   ├── admin/
   ├── api/
   ├── config/
   ├── css/
   ├── includes/
   ├── js/
   ├── Luxury Images/
   ├── pages/
   ├── sql/
   ├── templates/
   ├── uploads/
   ├── .htaccess
   ├── index.php
   ├── robots.txt
   └── sitemap.xml
   ```

## Step 4: Configure Environment

1. In File Manager, create `.env` file in `public_html/`
2. Copy this content and fill in your values:

```ini
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://yourdomain.com
DB_HOST=localhost
DB_PORT=3306
DB_NAME=viata_luxe
DB_USER=your_cpanel_db_user
DB_PASS=your_strong_password
DB_CHARSET=utf8mb4
ADMIN_TIMEOUT=1800
```

## Step 5: Set Permissions

1. File Manager → select `uploads/` folder → **Permissions**: 755
2. File Manager → select `Luxury Images/` folder → **Permissions**: 755
3. Create `logs/` folder → **Permissions**: 755
4. Create `cache/` folder → **Permissions**: 755

## Step 6: Configure Domain

1. cPanel → **Addon Domains** or **Subdomains** (depending on setup)
2. Point your domain to `public_html/` (or the subfolder)
3. Ensure SSL is enabled (Let's Encrypt via cPanel)

## Step 7: Verify

1. Visit `https://yourdomain.com` — homepage should load
2. Visit `https://yourdomain.com/about` — About page
3. Visit `https://yourdomain.com/contact` — Contact page
4. Visit `https://yourdomain.com/admin/login` — Admin panel
   - Login: `admin` / `ViataLuxe2025!`
5. Check all images load correctly

---

## File Structure Notes

| Path | Purpose | Size |
|------|---------|------|
| `Luxury Images/` | Hero/gallery images | 36.7 MB (75 files) |
| `uploads/` | CMS-uploaded images | 10.6 MB (15 files) |
| `sql/viata-luxe-full.sql` | Full DB dump | 129 KB |
| `config/app.php` | App config (reads .env) | — |
| `.env` | Production secrets | DO NOT commit |

## Admin URL

- Login: `https://yourdomain.com/admin/login`
- Default credentials: `admin` / `ViataLuxe2025!`
- **Change the admin password immediately after first login**

## Troubleshooting

- **500 error**: Check `.env` exists and DB credentials are correct
- **Images not loading**: Verify `Luxury Images/` and `uploads/` folders have 755 permissions
- **DB connection failed**: Ensure DB user has ALL PRIVILEGES on the database
- **Rewrite not working**: Verify `.htaccess` is uploaded and `mod_rewrite` is enabled
