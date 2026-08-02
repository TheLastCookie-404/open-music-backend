#!/bin/bash
set -e

echo "Start building images"

echo "[--- Building app image ---]"
docker build -t thelastcookie404/openmusic-app:latest .
echo "[--- App image built! ---]"

echo "[--- Building web image (nginx) ---]"
docker build -t thelastcookie404/openmusic-web:latest -f docker/nginx/Dockerfile .
echo "[--- web image built! ---]"