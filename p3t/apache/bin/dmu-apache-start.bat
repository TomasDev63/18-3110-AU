@echo off
rem ######################################
rem # pdn: 2009-09-22:
rem # cfi: unpadetd 2019-09-26
rem # H:\p3t parameterised to P3TDIR

rem faculty pc settings (probe for \User directory)
IF EXIST C:\User set INSTALLDIR=C:\User

set P3TDIR=H:\p3t

IF NOT EXIST %P3TDIR%\ GOTO missing_dirs
IF NOT EXIST %P3TDIR%\phpappfolder\public_php GOTO missing_dirs
IF NOT EXIST %P3TDIR%\phpappfolder\includes GOTO missing_dirs
IF NOT EXIST %P3TDIR%\phpappfolder\var\session GOTO missing_dirs
IF NOT EXIST %P3TDIR%\phpappfolder\var\upload GOTO missing_dirs
IF NOT EXIST %P3TDIR%\phpappfolder\var\wsdl-cache GOTO missing_dirs

echo ------------------------------------    
echo Apache server starting 
echo    http:// on port 6789.
echo To request a page from the server, use the URL:
echo   http://localhost:6789/
echo 
echo To stop the Apache server, select this window and press CTRL-C
echo   (then Y when asked if you want to terminate the batch job)
    
set PATH=%INSTALLDIR%/php
httpd.exe

echo 
echo Apache server stopped.
echo 
    
@echo on
pause
exit

:missing_dirs
echo
echo Cannot start the server because at least one of the following directories does not exist:
echo     %P3TDIR%\ 
echo     %P3TDIR%\phpappfolder\public_php 
echo     %P3TDIR%\phpappfolder\includes
echo     %P3TDIR%\phpappfolder\var\session
echo     %P3TDIR%\phpappfolder\var\upload
echo     %P3TDIR%\phpappfolder\var\wsdl-cache
echo
@echo on
pause
exit


IF NOT EXIST %P3TDIR%\apache\conf\extra\dmu-httpd-p3t.conf GOTO no_custom_httpd_conf
echo 
echo Copying custom dmu-httpd-p3t.conf from %P3TDIR%\apache\conf\extra\ to %INSTALLDIR%\apache\conf\extra\ 
copy %P3TDIR%\apache\conf\extra\dmu-httpd-p3t.conf %INSTALLDIR%\apache\conf\extra\dmu-httpd-p3t.conf

:no_custom_httpd_conf
IF NOT EXIST %P3TDIR%\apache\htdocs\index.html GOTO no_custom_htdocs_index
echo 
echo Copying custom index.html from %P3TDIR%\apache\htdocs\ to %INSTALLDIR%\apache\htdocs\ 
copy %P3TDIR%\apache\htdocs\index.html %INSTALLDIR%\apache\htdocs\index.html

:no_custom_htdocs_index
IF NOT EXIST %P3TDIR%\php\php.ini GOTO no_custom_php_ini
echo 
echo Copying custom php.ini from %P3TDIR%\php\ to %INSTALLDIR%\php\ 
copy %P3TDIR%\php\php.ini %INSTALLDIR%\php\php.ini

:no_custom_php_ini
