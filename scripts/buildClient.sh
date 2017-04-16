#!/usr/bin/env bash
# Install Front-end dependencies for View.js
docker exec -it o2n_client npm install
# Build the production environment
docker exec -it o2n_client npm run build