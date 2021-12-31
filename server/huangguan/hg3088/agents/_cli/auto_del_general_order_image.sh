#!/bin/sh
#mtime — 文件内容上次修改时间
#atime — 文件被读取或访问的时间
#ctime — 文件状态变化时间
find /www/huangguan/hg3088/agents/images/order_image -type d -mtime +15 | xargs rm -rf
find /tmp -name "xvfb-run.*" -type d -mmin +1 | xargs rm -rf