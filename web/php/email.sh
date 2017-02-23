#!/bin/sh

echo "I HAVE BEEN EXECUTED!" >> email.log
echo "Please click this link: https://dinen.ddns.net/php_scripts/confirm.php?key=$2" | mail -s "Dinen Confirmation" "$1"
