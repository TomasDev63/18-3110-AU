SET INSTALLDIR=Z:\Unknown
IF EXIST C:\User SET INSTALLDIR=C:\User
IF EXIST D:\User SET INSTALLDIR=D:\User
IF EXIST C:\p3t  SET INSTALLDIR=C:\p3t

%INSTALLDIR%\mysql\bin\mysql.exe -u root -t -vvv < create_@USERID@db.sql > output_from_create_@USERID@db.txt
pause

%INSTALLDIR%\mysql\bin\mysql.exe -u @USERID@ -p@REDPW@ -t -vvv < setup_@USERID@db.sql > output_from_setup_@USERID@db.txt
pause

%INSTALLDIR%\mysql\bin\mysql.exe -u @USERID@ -p@REDPW@ -t -vvv < populate_@USERID@db.sql > output_from_populate_@USERID@db.txt
pause

%INSTALLDIR%\mysql\bin\mysql.exe -u @USERID@ -p@REDPW@ -t -vvv < grant_priv_on_development_@USERID@db.sql > output_from_grant_priv_on_development_@USERID@db.txt
pause
exit

