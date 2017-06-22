#!/usr/bin/env bash

# Run the installation script
ssh -o "StrictHostKeyChecking=no" $o2n_user@$o2n_host ./scripts/installStaging.sh