#!/usr/bin/bash

kill `ps -T \`cat /usr/local/phpci/phpci.daemonise.pid\` | sed 's/^ *\([0-9]*\).*/\1/' | tr  '\n' ' '`