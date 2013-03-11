#!/bin/bash

if [[ $UID -ne 0 ]]; then
    echo "$0 must be run as root"
    exit 1
fi

BASE_PATH="/home/birdylee/Software/hosts/"
#TARGET_FILE="/home/jacky/test_hosts"
TARGET_FILE="/etc/hosts"
STATIC_HOSTS_FILE="/home/birdylee/Software/hosts/static"
ENV_HOSTS_FILE=""

ENV="$1"

usage(){
    echo "Usage: $0 ENV"
    echo "       ENV : Name of hosts file, qa,stg,etc."
    exit -1
}

if [ ! "$ENV" ]; then
    echo "Please input env param!"
    usage
fi

ENV_HOSTS_FILE="${BASE_PATH}${ENV}"

if [ ! -f "$ENV_HOSTS_FILE" ]; then
    echo "${ENV_HOSTS_FILE} not found, Please check it!"
    usage
fi

cat "$STATIC_HOSTS_FILE" > "$TARGET_FILE"
echo "################${ENV} hosts#################" >> "$TARGET_FILE"
cat "$ENV_HOSTS_FILE" >>  "$TARGET_FILE"

/etc/init.d/dns-clean restart

echo "Done!"
