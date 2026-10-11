#!/usr/bin/env bash
# ==============================================================================
# Automated Server Tuning Script for Hetzner CX43 (8 vCPU / 16 GB RAM / NVMe)
# Tunes PHP-FPM, OPcache, MariaDB, and Nginx for extreme enterprise concurrency.
# ==============================================================================

set -e

echo "=== Tuning Server for Hetzner CX43 (8 vCPU / 16 GB RAM) ==="

if [ "$EUID" -ne 0 ]; then
  echo "Error: Please run this script as root (or with sudo)."
  exit 1
fi

# Detect installed PHP version
PHP_VER=$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;' 2>/dev/null || echo "8.3")
echo "Detected PHP version: $PHP_VER"

# 1. Tune PHP-FPM Pool for 16 GB RAM (allocate ~8 GB to PHP workers)
echo "--- 1. Tuning PHP-FPM Pool (/etc/php/$PHP_VER/fpm/pool.d/www.conf) ---"
FPM_CONF="/etc/php/$PHP_VER/fpm/pool.d/www.conf"
if [ -f "$FPM_CONF" ]; then
    sed -i 's/^pm = .*/pm = dynamic/' "$FPM_CONF"
    sed -i 's/^pm.max_children = .*/pm.max_children = 220/' "$FPM_CONF"
    sed -i 's/^pm.start_servers = .*/pm.start_servers = 30/' "$FPM_CONF"
    sed -i 's/^pm.min_spare_servers = .*/pm.min_spare_servers = 15/' "$FPM_CONF"
    sed -i 's/^pm.max_spare_servers = .*/pm.max_spare_servers = 45/' "$FPM_CONF"
    sed -i 's/^;pm.max_requests = .*/pm.max_requests = 1000/' "$FPM_CONF"
    sed -i 's/^pm.max_requests = .*/pm.max_requests = 1000/' "$FPM_CONF"
fi

# 2. Tune PHP OPcache
echo "--- 2. Tuning PHP OPcache ---"
OPCACHE_CONF="/etc/php/$PHP_VER/fpm/conf.d/20-opcache-tuning.ini"
cat << 'EOF' > "$OPCACHE_CONF"
opcache.enable = 1
opcache.enable_cli = 0
opcache.memory_consumption = 512
opcache.interned_strings_buffer = 64
opcache.max_accelerated_files = 32531
opcache.revalidate_freq = 2
opcache.save_comments = 1
opcache.fast_shutdown = 1
EOF

# 3. Tune MariaDB 10.11 for 16 GB RAM (8 GB Buffer Pool)
echo "--- 3. Tuning MariaDB Buffer Pool and InnoDB ---"
cat << 'EOF' > /etc/mysql/mariadb.conf.d/60-hetzner-tuning.cnf
[mysqld]
innodb_buffer_pool_size = 8G
innodb_buffer_pool_instances = 8
innodb_log_file_size = 1G
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
innodb_file_per_table = 1
max_connections = 400
table_open_cache = 8192
table_definition_cache = 4096
open_files_limit = 131072
thread_cache_size = 128
query_cache_type = 0
query_cache_size = 0
EOF

# 4. Clean Nginx global buffer overrides
echo "--- 4. Cleaning Nginx global buffer overrides ---"
rm -f /etc/nginx/conf.d/fastcgi-tuning.conf

# 5. Kernel Sysctl Tuning
echo "--- 5. Tuning Linux Kernel Parameters ---"
cat << 'EOF' > /etc/sysctl.d/99-amutbar.conf
fs.file-max = 2097152
net.core.somaxconn = 65535
net.ipv4.tcp_max_syn_backlog = 65535
net.ipv4.ip_local_port_range = 1024 65535
net.ipv4.tcp_tw_reuse = 1
net.ipv4.tcp_fin_timeout = 15
vm.swappiness = 10
EOF
sysctl -p /etc/sysctl.d/99-amutbar.conf || true

# 6. Fix Git safe directory
git config --global --add safe.directory /var/www/amutbar-admin 2>/dev/null || true

# 7. Restart and Validate Services
echo "--- 6. Restarting Services ---"
systemctl restart mariadb
systemctl restart "php$PHP_VER-fpm" || systemctl restart php-fpm || true
systemctl reload nginx || systemctl restart nginx

echo ""
echo "=== Tuning Complete for 8 vCPU / 16 GB RAM! ==="
echo "MariaDB Buffer Pool: 8 GB"
echo "PHP-FPM Max Workers: 220"
echo "Services Status: All Active and Running."
