#!/bin/sh

if [ -f '/www/huangguan/hg3088/agents/_cli/flushWay/UJL.locks' ]; then
    step=20
    for (( i = 0; i < 60; i=(i+step) )); do
        php '/www/huangguan/hg3088/agents/app/agents/downdata_ujl/ft/FT_ROLL_TW.php'
        sleep $step
    done
    exit 0
else
    step=20
    for (( i = 0; i < 60; i=(i+step) )); do
        php '/www/huangguan/hg3088/agents/app/agents/downdata_ra/ft/FT_RE_tw.php'
        sleep $step
    done
    exit 0
fi


