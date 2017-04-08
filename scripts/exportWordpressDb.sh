#!/bin/bash
docker run --name wordpress_backup --link o2n_wordpress_db -v config:/backup confirm/mysql-backup