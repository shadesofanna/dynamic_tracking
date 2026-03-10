# Deployment Configuration Guide

## Pre-Deployment Checklist

### 1. Environment Variables Setup
```bash
# Copy and configure the environment file
cp .env.example .env
```

**Edit `./.env` with production values:**
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_HOST=your_db_host
DB_NAME=dynamic_pricing_db
DB_USER=db_user
DB_PASSWORD=strong_password

SMTP_USERNAME=your_email@gmail.com
SMTP_PASSWORD=app_specific_password
SMTP_FROM_EMAIL=noreply@yourdomain.com

SECRET_KEY=generate_long_random_string
```

### 2. Directory Permissions

```bash
# Make directories writable for file uploads and logs
chmod -R 755 /path/to/dynamic_pricing/
chmod -R 777 /path/to/dynamic_pricing/logs/
chmod -R 777 /path/to/dynamic_pricing/public/assets/images/uploads/
```

### 3. Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE dynamic_pricing_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'db_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON dynamic_pricing_db.* TO 'db_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import database schema (if you have a migration/seed file)
mysql -u db_user -p dynamic_pricing_db < database_schema.sql
```

### 4. Web Server Configuration

**Apache (.htaccess already configured):**
- Ensure `mod_rewrite` is enabled
- Document root should point to `public/` folder

**Nginx Configuration:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/dynamic_pricing/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### 5. SSL/HTTPS Configuration

- Obtain SSL certificate (Let's Encrypt recommended)
- Update `APP_URL` in `.env` to use `https://`
- Configure server to redirect HTTP to HTTPS

### 6. Security Hardening

```bash
# Protect sensitive files
chmod 644 .env
chmod 644 .htaccess

# Disable directory listing
echo "Options -Indexes" > public/.htaccess

# Set secure permissions
chmod 640 config/*.php
chmod 640 core/*.php
chmod 640 utils/*.php
```

### 7. PHP Configuration

**php.ini settings for production:**
```ini
; Display errors only in logs, never to users
display_errors = Off
log_errors = On
error_log = /path/to/logs/error.log

; Session security
session.cookie_secure = 1
session.cookie_httponly = 1
session.cookie_samesite = Strict

; Security
register_globals = Off
magic_quotes_gpc = Off

; Performance
max_execution_time = 300
upload_max_filesize = 5M
post_max_size = 5M

; Database
default_socket_timeout = 60
```

### 8. Email Configuration

For Gmail SMTP:
1. Enable 2-Factor Authentication
2. Generate App Password in Google Account Settings
3. Use app password in `.env` SMTP_PASSWORD

### 9. Cron Jobs Setup

Add these to your cron scheduler:
```bash
# Update exchange rates (hourly)
0 * * * * /usr/bin/php /path/to/dynamic_pricing/cron/update_exchange_rates.php

# Update prices (every 4 hours)
0 */4 * * * /usr/bin/php /path/to/dynamic_pricing/cron/update_prices.php

# Check inventory and update prices (daily at 2 AM)
0 2 * * * /usr/bin/php /path/to/dynamic_pricing/cron/check_inventory_and_update_prices.php

# Generate analytics (daily at 3 AM)
0 3 * * * /usr/bin/php /path/to/dynamic_pricing/cron/generate_analytics.php

# Send notifications (every 30 minutes)
*/30 * * * * /usr/bin/php /path/to/dynamic_pricing/cron/send_notifications.php
```

### 10. Monitoring & Logging

```bash
# Monitor error logs
tail -f /path/to/logs/error.log

# Monitor application logs
tail -f /path/to/logs/app.log

# Check disk space
df -h

# Monitor PHP-FPM
ps aux | grep php-fpm
```

## Critical Security Points

✅ **NEVER commit `.env` to version control**
✅ **Use strong database passwords**
✅ **Enable HTTPS/SSL**
✅ **Set `APP_DEBUG=false` in production**
✅ **Use environment variables for all secrets**
✅ **Regularly backup the database**
✅ **Keep PHP and dependencies updated**
✅ **Monitor error logs regularly**
✅ **Use a firewall**
✅ **Keep uploads directory outside web root if possible**

## Configuration Files Modified

1. **config/config.php** - Now loads from environment variables
2. **config/database.php** - Now loads credentials from environment
3. **.env.example** - Updated with production template
4. **core/EnvLoader.php** - New environment loader class
5. **public/index.php** - Loads environment variables first

## Environment Variable Priority

1. System environment variables (highest priority)
2. .env file variables
3. Default values in code (lowest priority)

This ensures production servers can set environment variables without needing .env files.
