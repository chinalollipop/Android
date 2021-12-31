#!/bin/sh
step=20
for (( i = 0; i < 60; i=(i+step) )); do  
    php '/www/huangguan/hg3088/agents/_cli/get_game_video_ioratio.php'  
    sleep $step  
done  
exit 0  
