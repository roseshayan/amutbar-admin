#!/usr/bin/env bash
# ==============================================================================
# Automated Linux VPS Setup Script (Ubuntu 22.04 / 24.04 or Debian 12)
# Configures Nginx, PHP 8.2-FPM, MariaDB, Composer, Certbot, and OS tuning for Amutbar
# ==============================================================================

set -e

echo "=== Starting Amutbar Linux VPS Provisioning ==="

# Root privilege check
if [ "$EUID" -ne 0 ]; then
  echo "Error: Please run this script as root (or with sudo)."
  exit 1
fi

# 1. Update OS packages
echo "--- 1. Updating system packages ---"
apt-get update && apt-get upgrade -y
apt-get install -y software-properties-common curl git unzip zip rsync ufw htop net-tools ffmpeg

# 2. Add PHP repository and install PHP 8.2 with extensions
echo "--- 2. Installing PHP 8.2 and required extensions ---"
if ! grep -q "ondrej/php" /etc/apt/sources.list /etc/apt/sources.list.d/* 2>/dev/null; then
    add-apt-repository -y ppa:ondrej/php
    apt-get update
fi

apt-get install -y php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-curl \
    php8.2-mbstring php8.2-fileinfo php8.2-opcache php8.2-xml php8.2-zip php8.2-gd

# 3. Install Nginx and MariaDB
echo "--- 3. Installing Nginx and MariaDB ---"
apt-get install -y nginx mariadb-server certbot python3-certbot-nginx

# 4. Install Composer
echo "--- 4. Installing Composer ---"
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# 5. Configure UFW Firewall
echo "--- 5. Configuring UFW Firewall ---"
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw --force enable

# 6. Kernel Sysctl Tuning for High Concurrency
echo "--- 6. Tuning kernel parameters (sysctl) ---"
cat << 'EOF' > /etc/sysctl.d/99-amutbar.conf
fs.file-max = 2097152
net.core.somaxconn = 65535
net.ipv4.tcp_max_syn_backlog = 65535
net.ipv4.ip_local_port_range = 1024 65535
net.ipv4.tcp_tw_reuse = 1
net.ipv4.tcp_fin_timeout = 15
EOF
sysctl -p /etc/sysctl.d/99-amutbar.conf

# 7. Prepare Deployment Directory
DEPLOY_PATH="/var/www/amutbar-admin"
echo "--- 7. Preparing directory: $DEPLOY_PATH ---"
mkdir -p "$DEPLOY_PATH/storage/logs"
mkdir -p "$DEPLOY_PATH/storage/uploads/avatars"
mkdir -p "$DEPLOY_PATH/storage/uploads/videos"
mkdir -p "$DEPLOY_PATH/storage/uploads/tickets"
chown -R www-data:www-data "$DEPLOY_PATH"
chmod -R 775 "$DEPLOY_PATH/storage"

# 8. Configure OPcache
echo "--- 8. Configuring PHP OPcache ---"
cat << 'EOF' > /etc/php/8.2/fpm/conf.d/20-opcache-tuning.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
EOF

systemctl restart php8.2-fpm
systemctl restart nginx

echo "=== VPS Setup Completed Successfully! ==="
echo "Next step: copy nginx/amutbar.conf to /etc/nginx/sites-available/amutbar and enable it."
