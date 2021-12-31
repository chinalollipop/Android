#!/bin/sh
step=10
for (( i = 0; i < 60; i=(i+step) )); do
  php '/www/huangguan/hg3088/agents/_cli/vgqp/vgqp_get_bet_data.php'
  sleep $step
done
exit 0