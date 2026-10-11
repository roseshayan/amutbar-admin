#!/usr/bin/env bash
# ==============================================================================
# Linux VPS Resource Monitor for Amutbar Stress Testing
# Usage: chmod +x vps_monitor.sh && ./vps_monitor.sh
# Outputs real-time metrics to terminal and logs to stress_metrics_*.csv
# ==============================================================================

OUTPUT_FILE="stress_metrics_$(date +%Y%m%d_%H%M%S).csv"
INTERVAL=2 # Interval in seconds

echo "=== Starting Amutbar VPS Resource Monitor for Stress Testing ==="
echo "Output CSV file: $OUTPUT_FILE"
echo "Press Ctrl+C to stop monitoring."

# Write CSV header
echo "Timestamp,CPU_Usage_Percent,RAM_Used_MB,RAM_Free_MB,RAM_Usage_Percent,PHP_FPM_Processes,MySQL_Connections,Disk_IO_Wait" > "$OUTPUT_FILE"

trap cleanup INT
cleanup() {
    echo ""
    echo "=== Monitoring stopped. Results saved to $OUTPUT_FILE ==="
    exit 0
}

while true; do
    TIMESTAMP=$(date +"%Y-%m-%d %H:%M:%S")

    # 1. CPU Usage %
    CPU_USAGE=$(top -bn1 | grep "Cpu(s)" | sed "s/.*, *\([0-9.]*\)%* id.*/\1/" | awk '{print 100 - $1}')

    # 2. RAM Usage
    RAM_TOTAL=$(free -m | awk '/^Mem:/ {print $2}')
    RAM_USED=$(free -m | awk '/^Mem:/ {print $3}')
    RAM_FREE=$(free -m | awk '/^Mem:/ {print $4}')
    RAM_PERCENT=$(awk "BEGIN {printf \"%.1f\", ($RAM_USED/$RAM_TOTAL)*100}")

    # 3. Active PHP-FPM Workers
    PHP_COUNT=$(pgrep -c -f "php-fpm" || echo "0")

    # 4. MySQL Connections
    if command -v mysqladmin &> /dev/null; then
        MYSQL_CONN=$(mysqladmin status 2>/dev/null | awk '{print $4}' || echo "N/A")
    else
        MYSQL_CONN=$(ss -ant | grep -c ":3306 " || echo "N/A")
    fi

    # 5. Disk I/O Wait
    IO_WAIT=$(top -bn1 | grep "Cpu(s)" | awk '{print $10}' | sed 's/wa,//' || echo "0.0")

    # Print to Terminal (Clean English layout)
    printf "[%s] CPU: %5.1f%% | RAM: %4d MB (%4.1f%%) | PHP-FPM Workers: %3d | MySQL Conn: %s | I/O Wait: %s\n" \
        "$TIMESTAMP" "$CPU_USAGE" "$RAM_USED" "$RAM_PERCENT" "$PHP_COUNT" "$MYSQL_CONN" "$IO_WAIT"

    # Append to CSV
    echo "$TIMESTAMP,$CPU_USAGE,$RAM_USED,$RAM_FREE,$RAM_PERCENT,$PHP_COUNT,$MYSQL_CONN,$IO_WAIT" >> "$OUTPUT_FILE"

    sleep "$INTERVAL"
done
