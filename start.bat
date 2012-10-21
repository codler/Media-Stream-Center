@echo off
start /min vendor\php\php-cgi.exe -b 127.0.0.1:9000
start vendor\nginx\nginx.exe -c nginx.conf