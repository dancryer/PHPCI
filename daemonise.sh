#!/usr/bin/bash

cd /usr/local/phpci/phpci
PATH=/usr/local/phpci:$PATH
nohup php ./daemonise phpci:daemonise >/dev/null 2>&1 & echo $! > /usr/local/phpci/phpci.daemonise.pid
