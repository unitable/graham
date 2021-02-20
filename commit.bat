@echo off

set name=%date:~6,4%-%date:~3,2%-%date:~0,2% %time:~0,2%:%time:~3,2%:%time:~6,2%

git add . && git commit -m "%name%"
