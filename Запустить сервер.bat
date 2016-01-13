@net stop NginxTrayRu
@cd web-server\patch_production_php_5_4
call patch.bat
@cd ..\nginx-1.7.0
start NginxTrayRu_php_5_4.exe
start http://localhost