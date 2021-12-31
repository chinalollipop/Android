#!/bin/sh

    step=10
    for (( i = 0; i < 60; i=(i+step) )); do
        php '/www/huangguan/hg3088/agents/app/agents/accounts/fu2ft2_local.php'
        sleep $step
    done
    exit 0
