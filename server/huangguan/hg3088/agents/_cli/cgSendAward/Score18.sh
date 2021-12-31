#!/bin/sh

    step=6
    for (( i = 0; i < 60; i=(i+step) )); do
        php '/www/huangguan/hg3088/agents/app/agents/clearing/Score18.php'
        sleep $step
    done
    exit 0