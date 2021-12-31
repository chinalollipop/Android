#!/bin/sh
step=1
for (( i = 0; i < 60; i=(i+step) )); do  
    php '/www/huangguan/hg3088/agents/_cli/yzfautopayback.php'  
    sleep $step  
done  
exit 0  
