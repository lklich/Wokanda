# Wokanda
System obsługi wokand elektronicznych dla sądów 

Jest to gotowy system integracji systemu SAWA do obsługi wokand elektronicznych. Skrzypt powstał dawno temu. Został jnapisany w PHP i do tego niechlujnie. Typowe spaghetti code, pisane na szybko i bez planowanego wsparcia na dłuższe lata. Założeniem było napisać, zarobić i zapomnieć. Po kilku latach działania ani razu nie było problemów z działaniem, czego nie można powiedzieć o systemach komercyjnych, za które trzeba zapłacić kupę kasy i płacić kilkutysięczne, coroczne wsparcie. Przez kilka lat system ten działa z powodzeniem w kilku sądach. Nie jest to profesjonalny system z rezerwacją sal czy planowaniem, jednakże jest w zupełności wystarczający do sądów, w których nie wykorzystuje się tego typu funcjonlaności. 

Niniejszy system jest dostępny za darmo - także do użytku komercyjnego, jednakże nie udzielam bezpłatnego wsparcia na instalację i serwis. Proszę też pamiętać o licencji - nie wolno zarabiać na sprzedażhy kodu - ma być dostępny za darmo, zaś wszelkie zmiany, nowe wersje, pochodne systemu także muszą być dostępne za darmo!

Jak wdrożyć system w Windows Server?
1. Zainstalować Apache lub IIS oraz PHP w wersji min. 7.0
2. Zainstalować biblioteki ODBC dla PHP.
3. Wgrać widoki do każdej bazy, z której będą generowane wokandy.
4. Utworzyć bazę wokanda ze skryptu wokanda.sql.
5. Skonfigurować połączenia z bazami danych w pliku config.php
6. Uruchomoć generuj.bat - jeśli nie będzie błędów, dodać generuj.bat do harmonogramu co np. 10 minut.
7. Zmienić konfigurację wokand, kierując je na adres serwera i skryptu index.php lub zbiorcza.php (http://serwer/index.php?sala=xx), gdzie xx to numer sali rozpraw.

Po wdrożeniu system działa bezawaryjnie. 

Leszek Klich
tel. 691050123
