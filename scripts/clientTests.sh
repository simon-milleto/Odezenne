#!/usr/bin/env bash
# Install Front-end dependencies for View.js
docker exec -it o2n_client npm install
# Run unit tests
docker exec -it o2n_client npm run unit