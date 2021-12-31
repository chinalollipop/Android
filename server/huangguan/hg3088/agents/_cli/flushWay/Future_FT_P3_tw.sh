#!/bin/sh

if [ -f '/www/huangguan/hg3088/agents/_cli/flushWay/HuangGan.locks' ]; then
    php '/www/huangguan/hg3088/agents/app/agents/downdata_ra/ft_future/FT_F_P3_tw.php'
fi
