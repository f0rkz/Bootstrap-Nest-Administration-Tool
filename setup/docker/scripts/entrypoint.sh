#!/bin/bash
set -eo pipefail

[[ "${DEBUG}" == true ]] && set -x

check_database_connection() {
  echo "Initializing Nest Bootstrap container ..."
  echo ''
  echo "Attempting to connect to database ..."
  prog="mysqladmin -h ${DB_HOST} -u ${DB_USERNAME} ${DB_PASSWORD:+-p$DB_PASSWORD} -P ${DB_PORT} status"
  timeout=60
  while ! ${prog} >/dev/null 2>&1
  do
    timeout=$(( timeout - 1 ))
    if [[ "$timeout" -eq 0 ]]; then
      echo
      echo "Could not connect to database server! Aborting..."
      exit 1
    fi
    echo -n "."
    sleep 1
  done
  echo
}

init_mysqldb() {
    echo "Import DB Schema"
    mysql -h "${DB_HOST}" -u "${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}" < /opt/nest-bootstrap/setup/db/dbsetup.sql
}

collect_data() {
  echo "Running nest data collector ..."
  cd /opt/nest-bootstrap/includes/scripts; /usr/bin/php /opt/nest-bootstrap/includes/scripts/collect-nest-data.php
  touch /etc/crontab /etc/cron.*/*
  service cron start
}

start_system() {
  check_database_connection
  init_mysqldb
  collect_data
  echo "Starting Nest bootstrap! ..."
  /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
}

start_system

exit 0
