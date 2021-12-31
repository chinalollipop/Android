#!/bin/sh
step=20
for (( i = 0; i < 60; i=(i+step) )); do  
    php '/www/huangguan/hg3088/agents/_cli/gameDateUpdate_Today_DJFT.php'
    sleep $step  
done  
exit 0  
