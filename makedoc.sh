#!/bin/bash
rm -f -R Documentation
phpdoc -d . -f *.php -t Documentation -ti 'Fraudpointer PHP Client Library' -dn 'Fraudpointer PHP Demo Application' -o "HTML:frames:default" -s on -ric Device-Fingerprinting,Overview
