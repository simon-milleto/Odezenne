#!/usr/bin/env bash

# Generate Wordpress certificates
echo "### Generate Wordpress certificates ###"
docker run --rm -v "$(pwd)"/config/certs/wordpress:/app -it nginx:1.11 openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 -keyout /app/privateKey.key -out /app/certificate.crt
# docker run --rm -v "$(pwd)"/config/certs/wordpress:/app -it nginx:1.11 openssl dhparam -out /app/dhparam.pem 2048

# Generate Lumen certificates
echo "### Generate Lumen certificates ###"
docker run --rm -v "$(pwd)"/config/certs/lumen:/app -it nginx:1.11 openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 -keyout /app/privateKey.key -out /app/certificate.crt
# docker run --rm -v "$(pwd)"/config/certs/lumen:/app -it nginx:1.11 openssl dhparam -out /app/dhparam.pem 2048
