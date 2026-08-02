#!/bin/bash
set -e

echo "Start pushing images"

echo "[--- Pushing app image ---]"
docker push thelastcookie404/openmusic-app:latest
echo "[--- App image pushed! ---]"

echo "[--- Pushing web image ---]"
docker push thelastcookie404/openmusic-web:latest
echo "[--- Web image pushed! ---]"