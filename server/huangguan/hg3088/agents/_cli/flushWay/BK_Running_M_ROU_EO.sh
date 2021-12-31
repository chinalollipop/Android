#!/bin/sh
step=5
for (( i = 0; i < 60; i=(i+step) )); do  
    php '/www/huangguan/hg3088/agents/app/agents/downdata_ra/bk/BK_Running_M_ROU_EO.php'
    sleep $step
done  
exit 0  
