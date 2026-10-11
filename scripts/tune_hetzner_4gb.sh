#!/usr/bin/env bash
# ==============================================================================
# Automated Server Tuning Script for Hetzner CX23 (2 vCPU / 4 GB RAM / NVMe SSD)
# Tunes PHP 8.2-FPM, OPcache, MariaDB 10.11, and Nginx for maximum throughput.
# ==============================================================================

set -e

echo "=== Tuning Server for Hetzner CX23 (2 vCPU / 4 GB RAM) ==="

if [ "$EUID" -ne 0 ]; then
  echo "Error: Please run this script as root (or with sudo)."
  exit 1
fi

# 1. Tune PHP 8.2-FPM Pool
echo "--- 1. Tuning PHP-FPM Pool (/etc/php/8.2/fpm/pool.d/www.conf) ---"
FPM_CONF="/etc/php/8.2/fpm/pool.d/www.conf"
if [ -f "$FPM_CONF" ]; then
    sed -i 's/^pm = .*/pm = dynamic/' "$FPM_CONF"
    sed -i 's/^pm.max_children = .*/pm.max_children = 65/' "$FPM_CONF"
    sed -i 's/^pm.start_servers = .*/pm.start_servers = 15/' "$FPM_CONF"
    sed -i 's/^pm.min_spare_servers = .*/pm.min_spare_servers = 10/' "$FPM_CONF"
    sed -i 's/^pm.max_spare_servers = .*/pm.max_spare_servers = 25/' "$FPM_CONF"
    sed -i 's/^;pm.max_requests = .*/pm.max_requests = 1000/' "$FPM_CONF"
    sed -i 's/^pm.max_requests = .*/pm.max_requests = 1000/' "$FPM_CONF"
fi

# 2. Tune PHP OPcache
echo "--- 2. Tuning PHP OPcache ---"
cat << 'EOF' > /etc/php/8.2/fpm/conf.d/20-opcache-tuning.ini
opcache.enable = 1
opcache.enable_cli = 0
opcache.memory_consumption = 192
opcache.interned_strings_buffer = 32
opcache.max_accelerated_files = 16229
opcache.revalidate_freq = 2
opcache.save_comments = 1
opcache.fast_shutdown = 1
EOF

# 3. Tune MariaDB 10.11 for 4 GB RAM
echo "--- 3. Tuning MariaDB Buffer Pool and InnoDB ---"
cat << 'EOF' > /etc/mysql/mariadb.conf.d/60-hetzner-tuning.cnf
[mysqld]
innodb_buffer_pool_size = 1536M
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
innodb_file_per_table = 1
max_connections = 200
table_open_cache = 2000
open_files_limit = 65535
EOF

# 4. Clean any conflicting global Nginx buffer files (buffers are managed in site vhost)
echo "--- 4. Cleaning Nginx global buffer overrides ---"
rm -f /etc/nginx/conf.d/fastcgi-tuning.conf

# 5. Fix Git safe directory
echo "--- 5. Configuring Git safe directory ---"
git config --global --add safe.directory /var/www/amutbar-admin

# 6. Restart services
echo "--- 6. Reloading services ---"
systemctl restart php8.2-fpm
systemctl restart mariadb
nginx -t && systemctl reload nginx

echo "=== Optimization applied successfully! ==="
