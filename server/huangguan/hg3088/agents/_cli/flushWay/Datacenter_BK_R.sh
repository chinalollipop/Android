#!/bin/sh

step=5
for (( i = 0; i < 60; i=(i+step) )); do
    php '/www/huangguan/hg3088/agents/app/agents/downdata_ra/data_center/bk/BK_R.php'
    sleep $step
done
exit 0

