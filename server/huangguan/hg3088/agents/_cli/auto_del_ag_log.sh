#!/bin/sh
#mtime — 文件内容上次修改时间
#atime — 文件被读取或访问的时间
#ctime — 文件状态变化时间
find /tmp/group/ag -type f -mtime +15 | xargs rm -rf
