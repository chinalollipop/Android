#!/bin/sh
step=3
for (( i = 0; i < 60; i=(i+step) )); do  
    php '/www/huangguan/hg3088/agents/_cli/gameDateUpdate_Running_BK_M_ROU_EO_NOH1.php'
    sleep $step  
done  
exit 0  
