#/bin/sh
today=`date +%Y%m%d`
mysqldump -uoa -p6f7jN1Gi oa | gzip > /b/domains/oa.luckystardust.com/db_backup/oa."$today".sql.gz
