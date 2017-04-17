#!/usr/bin/env bash

# Going to the parent folder
cd ./..

# Install Front-end dependencies for Vue.js
echo "### Installing Front-end depenencies for Vue.js ###"
docker run --rm -v "$(pwd)"/client:/app -w /app node npm install

# Create the Lumen .env file
echo "### Creating the Lumen .env file ###"
cp "$(pwd)"/api/lumen/.env.staging "$(pwd)"/api/lumen/.env

# Install Back-end dependencies for Lumen
echo "### Installing Back-end dependencies for Lumen ###"
docker run --rm -v "$(pwd)"/api/lumen:/app -w /app composer/composer install
