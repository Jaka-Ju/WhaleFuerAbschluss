#!/bin/bash
# Lade die neueste Version von Docker Compose herunter
DOCKER_CONFIG=${DOCKER_CONFIG:-$HOME/.docker}
mkdir -p $DOCKER_CONFIG/cli-plugins
curl -SL https://github.com/docker/compose/releases/download/v2.40.1/docker-compose-linux-x86_64 -o $DOCKER_CONFIG/cli-plugins/docker-compose

# Setze die Ausführungsberechtigung
chmod +x $DOCKER_CONFIG/cli-plugins/docker-compose

# Überprüfe die Installation
docker compose version
