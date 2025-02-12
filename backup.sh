#!/bin/bash
TIMESTAMP=$(date +"%F-%H-%M-%S")
BACKUP_FILE="/backups/${MYSQL_DATABASE}_${TIMESTAMP}.sql"

echo "Starting backup for database ${MYSQL_DATABASE}..." > /backups/backup.log

# Run mysqldump and redirect both stdout and stderr to the log
mysqldump -h mariadb-container -u${MYSQL_USER} -p${MYSQL_PASSWORD} ${MYSQL_DATABASE} --skip-ssl > ${BACKUP_FILE} 2>> /backups/backup.log

if [ $? -eq 0 ]; then
  echo "Backup completed: ${BACKUP_FILE}" >> /backups/backup.log
else
  echo "Backup failed. Check the log for errors." >> /backups/backup.log
fi

# Keep only the 7 most recent backups and remove the rest
ls -t /backups/*.sql | tail -n +8 | xargs rm
