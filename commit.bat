@echo off

set bklog=%date:~6,4%-%date:~3,2%-%date:~0,2%_%time:~0,2%%time:~3,2%

git add . && git commit -m "%bklog%"
