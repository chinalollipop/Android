#!/bin/sh
step=10
for (( i = 0; i < 60; i=(i+step) )); do  
    sh '/www/huangguan/hg3088/agents/_cli/gameDateUpdate_Auto_Del_Statichtml.sh'  
    sleep $step  
done  
exit 0  
