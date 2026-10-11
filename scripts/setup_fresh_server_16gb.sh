#!/usr/bin/env bash
# ==============================================================================
# 1-Click Complete Setup for Fresh Hetzner CX43 (8 vCPU / 16 GB RAM / Ubuntu 24.04)
# Clones repository, installs Nginx + PHP 8.3 + MariaDB, seeds DB, tunes for 16GB.
# ==============================================================================

set -e

echo "=== Starting Complete Amutbar Setup for Fresh CX43 (8 vCPU / 16 GB RAM) ==="

if [ "$EUID" -ne 0 ]; then
  echo "Error: Please run as root."
  exit 1
fi

export DEBIAN_FRONTEND=noninteractive

# 1. Update OS & Install Dependencies
echo "--- 1. Updating System Packages ---"
apt-get update -y
apt-get install -y git curl unzip zip ufw htop net-tools software-properties-common apache2-utils

# 2. Install Nginx, MariaDB, and PHP
echo "--- 2. Installing Nginx, MariaDB & PHP ---"
apt-get install -y nginx mariadb-server

# Detect or install PHP (Ubuntu 24.04 has PHP 8.3 by default)
apt-get install -y php-fpm php-cli php-mysql php-curl php-mbstring php-xml php-zip php-gd php-opcache

PHP_VER=$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;' 2>/dev/null || echo "8.3")
echo "Installed PHP version: $PHP_VER"

# 3. Setup Project Directory
echo "--- 3. Setting Up Project Codebase ---"
mkdir -p /var/www/amutbar-admin
if [ ! -d "/var/www/amutbar-admin/.git" ]; then
    rm -rf /var/www/amutbar-admin
    git clone https://github.com/roseshayan/amutapp.git /var/www/amutbar-admin
else
    cd /var/www/amutbar-admin
    git pull origin main
fi

cd /var/www/amutbar-admin
git config --global --add safe.directory /var/www/amutbar-admin

# Create required writable directories
mkdir -p storage/logs storage/cache
chmod -R 777 storage

# 4. Setup Database
echo "--- 4. Initializing MariaDB Database ---"
mariadb -e "CREATE DATABASE IF NOT EXISTS amutbar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mariadb -e "CREATE USER IF NOT EXISTS 'amutbar'@'localhost' IDENTIFIED BY 'amut_test_pass_123';"
mariadb -e "GRANT ALL PRIVILEGES ON amutbar.* TO 'amutbar'@'localhost';"
mariadb -e "FLUSH PRIVILEGES;"

# Run migrations
echo "--- 5. Running Database Migrations ---"
php scripts/run_migrations.php

# 5. Configure Nginx
echo "--- 6. Configuring Nginx VirtualHost ---"
rm -f /etc/nginx/sites-enabled/default
cp nginx/amutbar.conf /etc/nginx/sites-available/amutbar

# Adjust PHP socket version in nginx/amutbar.conf if needed
sed -i "s|php8\.[0-9]-fpm\.sock|php${PHP_VER}-fpm.sock|g" /etc/nginx/sites-available/amutbar

ln -sf /etc/nginx/sites-available/amutbar /etc/nginx/sites-enabled/amutbar
nginx -t

# 6. Apply 16GB Extreme Tuning
echo "--- 7. Applying 16GB / 8 vCPU Tuning ---"
chmod +x scripts/tune_hetzner_16gb.sh
./scripts/tune_hetzner_16gb.sh

echo ""
echo "=========================================================================="
echo "=== Complete Setup & 16 GB Tuning Succeeded! ==="
echo "=========================================================================="
echo "Server IP:      $(curl -s http://checkip.amazonaws.com || hostname -I | awk '{print $1}')"
echo "Web Dashboard:  http://$(curl -s http://checkip.amazonaws.com || hostname -I | awk '{print $1}')/stress-dashboard.php"
echo "Swagger API:    http://$(curl -s http://checkip.amazonaws.com || hostname -I | awk '{print $1}')/swagger/"
echo "Database:       amutbar (All migrations applied)"
echo "Hardware:       8 vCPU | 16 GB RAM (MariaDB: 8GB Buffer Pool | PHP-FPM: 220 workers)"
echo "=========================================================================="
