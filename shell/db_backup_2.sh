#!/bin/bash
#This is a ShellScript For Auto DB Backup

#Setting
DBHost=your_db_host
DBName=your_db_name
DBUser=your_db_user_name
DBPasswd=your_db_user_password
# remember add the slash at the end!!!
BackupPath=/path/to/bak/
LogFile=/path/to/bak/db.log
DBPath=/path/to/db/
BackupMethod=mysqldump
#BackupMethod=mysqlhotcopy
#BackupMethod=tar
#Setting End


NewFile="$BackupPath$DBName"$(date +%y%m%d).tgz
DumpFile="$BackupPath$DBName"$(date +%y%m%d).sql
NewFileName="$DBName"$(date +%y%m%d).sql
OldFile="$BackupPath$DBName"$(date +%y%m%d --date='90 days ago').tgz

echo "-------------------------------------------" >> $LogFile
echo $(date +"%y-%m-%d %H:%M:%S") >> $LogFile
echo "--------------------------" >> $LogFile
#Delete Old File
if [ -f $OldFile ]
then
        rm -f $OldFile >> $LogFile 2>&1
        echo "[$OldFile]Delete Old File Success!" >> $LogFile
else
        echo "[$OldFile]No Old Backup File!" >> $LogFile
fi

if [ -f $NewFile ]
then
        echo "[$NewFile]The Backup File is exists,Can't Backup!" >> $LogFile
else
        if [ -z $DBPasswd ]
        then
                mysqldump -h $DBHost -u $DBUser --default-character-set=utf8 --opt $DBName > $DumpFile
        else
                mysqldump -h $DBHost -u $DBUser --default-character-set=utf8 -p$DBPasswd --opt $DBName > $DumpFile
        fi
        cd $BackupPath
        tar czvf $NewFile $NewFileName >> $LogFile 2>&1
        echo "[$NewFile]Backup Success! [$NewFileName]  [$DumpFile]" >> $LogFile
        rm -rf $DumpFile
fi

echo "-------------------------------------------" >> $LogFile
