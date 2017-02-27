#!/bin/bash

mysql --user=teamdinen --password=dinenx3 -D dinen -e "DELETE FROM jwt_blacklist WHERE exp < unix_timestamp(now());"
