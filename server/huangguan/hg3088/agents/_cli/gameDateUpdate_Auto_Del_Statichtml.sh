#!/bin/sh
#mmin +2 查找文件更新日时在距现在时刻二分以上的文件
#删除12s之前创建的文件
#删除30s之前创建的文件
#删除90s之前创建的文件
#删除12s之前创建的文件
#删除30s之前创建的文件
#删除90s之前创建的文件


find /www/huangguan/hg3088/member_new/app/member/FT_browse -name "running*.html" -mmin +0.2 | xargs rm -rf  
find /www/huangguan/hg3088/member_new/app/member/FT_browse -name "today*.html" -mmin +0.5 | xargs rm -rf	
find /www/huangguan/hg3088/member_new/app/member/FT_future -name "future*.html" -mmin +1.5 | xargs rm -rf	

find /www/huangguan/hg3088/member_new/app/member/BK_browse -name "running*.html" -mmin +0.2 | xargs rm -rf  
find /www/huangguan/hg3088/member_new/app/member/BK_browse -name "today*.html" -mmin +0.5 | xargs rm -rf 	
find /www/huangguan/hg3088/member_new/app/member/BK_future -name "future*.html" -mmin +1.5 | xargs rm -rf 