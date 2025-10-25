#!/bin/bash
set -e

# ---------------------------
# Pi-hole Setup Script (Option 1)
# ---------------------------

# 1️⃣ Prüfen, ob systemd-resolved läuft
echo "Prüfe Port 53..."
if ss -ulpn | grep -q ":53"; then
    echo "Port 53 wird aktuell belegt. Stoppe systemd-resolved..."
    systemctl stop systemd-resolved
    systemctl disable systemd-resolved
fi

# 2️⃣ Backup von resolv.conf
echo "Backup von /etc/resolv.conf erstellen..."
cp /etc/resolv.conf /etc/resolv.conf.backup || true

# 3️⃣ Neue resolv.conf anlegen
echo "Neue /etc/resolv.conf setzen..."
cat <<EOL > /etc/resolv.conf
nameserver 1.1.1.1
nameserver 8.8.8.8
EOL

echo "Starte Pi-hole..."
docker compose up -d

