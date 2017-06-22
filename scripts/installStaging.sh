#!/usr/bin/env bash

# Go to the installation folder
cd ~/odezenne/

# Get the latest version via git
echo "### Getting the latest version via git ###"
git fetch origin
git reset origin/master --hard

# Install Front-end dependencies for Vue.js
echo "### Installing Front-end depenencies for Vue.js ###"
docker run --rm -v "$(pwd)"/client:/app -w /app node yarn

# Build the staging environment
echo "### Building the Staging environment for Vue.js ###"
docker run --rm -v "$(pwd)"/client:/app -w /app node npm run build:staging

# Create the Lumen .env file
echo "### Creating the Lumen .env file ###"
cp "$(pwd)"/api/lumen/.env.staging "$(pwd)"/api/lumen/.env

# Install Back-end dependencies for Lumen
echo "### Installing Back-end dependencies for Lumen ###"
docker run --rm -v "$(pwd)"/api/lumen:/app -w /app composer/composer install

# Rebuilding and Restarting the docker containers
set -a
source .env
docker-compose -f docker-compose--staging.yml stop
docker-compose -f docker-compose--staging.yml up -d --build

# Setup Lumen database
docker exec -i o2n_lumen php artisan migrate:install
docker exec -i o2n_lumen php artisan migrate

# Import Wordpress database
echo "### Importing Wordpress database ###"
docker exec -i o2n_wordpress_db mysql -u wordpress -ppassword wordpress < config/wp_dump--staging.sql
