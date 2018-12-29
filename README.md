# Wokanda
System obsługi wokand elektronicznych dla sądów 

Jest to gotowy system integracji systemu SAWA do obsługi wokand elektronicznych. Skrzypt powstał dawno temu. Został jnapisany w PHP i do tego niechlujnie. Typowe spaghetti code, pisane na szybko i bez planowanego wsparcia na dłuższe lata. Założeniem było napisać, zarobić i zapomnieć. Po kilku latach działania ani razu nie było problemów z działaniem, czego nie można powiedzieć o systemach komercyjnych, za które trzeba zapłacić kupę kasy i płacić kilkutysięczne, coroczne wsparcie. Przez kilka lat system ten działa z powodzeniem w kilku sądach. Nie jest to profesjonalny system z rezerwacją sal czy planowaniem, jednakże jest w zupełności wystarczający do sądów, w których nie wykorzystuje się tego typu funcjonlaności. 

Niniejszy system jest dostępny za darmo - także do użytku komercyjnego, jednakże nie udzielam bezpłatnego wsparcia na instalację i serwis. Proszę też pamiętać o licencji - nie wolno zarabiać na sprzedażhy kodu - ma być dostępny za darmo, zaś wszelkie zmiany, nowe wersje, pochodne systemu także muszą być dostępne za darmo!

Technologia
System WOKANDA został napisany w języku PHP, JavaScript i T-Sql, z użyciem bibliotek Bootstrap i JQuery. System działa w architekturze klient-serwer i JEST ZGODNY Z SYSTEMEM wymaga do pracy serwera MS SQL (SAWA) + dodatkowa baza wokanda (Integrator). Do prezentacji wokand wymagana jest elektroniczna wokanda z przeglądarką internetową (testowane na Mozilla Firefox, Chrome, Edge oraz Opera). Nie gwarantuje się w pełni poprawnego działania na przeglądarkach Internet Explorer, ze względu na jej niekompatybilność z przyjętymi standardami.

Wymagania
Do prawidłowego działania, WOKANDA wymaga serwera HTTP, interpretera PHP oraz serwera SQL (MS SQL). Istnieją trzy możliwości konfiguracji środowiska jeśli chodzi o serwer WWW, jednak najlepszym rozwiązaniem jest IIS lub Apache – np. z pakietu XAMPP. Ostatnim rozwiązaniem jest użycie serwera wbudowanego w PHP.

Instalacja i konfiguracja
Przed instalacją programu, należy przygotować serwer baz danych, który może znajdować się na tym samym komputerze lub innym. Serwer MS SQL może mieć włączone logowanie domenowe, ale najlepiej, jeśli będzie to uwierzytelnianie wewnętrzne – czyli nazwa użytkownika i hasło. Jeśli baza wokanda znajduje się na tym samym komputerze co SAWA, dostęp do baz wydziałowych można wykonać jako tylko do odczytu, natomiast dla bazy wokanda – odczyt/zapis.

Następnym krokiem jest instalacja skryptów tworzących widoki w każdej bazie wydziałowej na bazach SAWA (karny, rodzinny, cywilny) Jeśli serwer oraz baza danych jest przygotowana poprawnie, można przystąpić do instalacji właściwego systemu.

Instalacja programu polega na przekopiowaniu plików systemu do katalogu WWW – widocznego dla serwera internetowego (Apache- c:\inetpub\wwwroot\wokanda). Kolejnym krokiem jest konfiguracja połączenia z bazami danych. Odbywa się to za pomocą pliku config.php, który należy edytować programem obsługującym pliki w kodowaniu UTF (np. notepad++).

Fragment rozporządzenia, który reguluje zawartość wokand sądowych:
1. Sekretariat sporządza wokandę przed terminem posiedzenia lub rozprawy w formie papierowej lub w postaci elektronicznej.
2. Przed salą, w której odbywa się posiedzenie jawne lub rozprawa, umieszcza się egzemplarz wokandy w formie papierowej, chyba że wokanda sporządzona jest w postaci elektronicznej i wyświetlana na monitorze umieszczonym przed salą.
3. Na wokandzie wskazuje się zaistniałe zmiany planowanego czasu rozpoznania danej sprawy oraz zaznacza numery spraw rozpoznanych w danym dniu niezwłocznie po zakończeniu każdej z nich.
§ 70. 1. Wokanda zawiera imiona i nazwiska sędziów, asesorów sądowych oraz ławników, sygnatury akt spraw wyznaczonych do rozpoznania w danym dniu, oznaczenie godzin, na które sprawy wyznaczono, imiona i nazwiska, nazwy lub firmy stron lub uczestników postępowania nieprocesowego. W postępowaniu karnym i w sprawach o wykroczenia podaje się ponadto sygnaturę akt oskarżyciela publicznego.
2. W razie wezwania na posiedzenie lub rozprawę świadków, biegłych i tłumaczy na wokandzie wskazuje się ich imiona i nazwiska wraz z oznaczeniem godziny, na którą są wezwani.
3. W sprawach opiekuńczych osób małoletnich, rozpoznawanych przy drzwiach zamkniętych, nie podaje się na wokandzie danych małoletniego.
4. Z uwagi na ochronę moralności, bezpieczeństwa państwa i porządku publicznego oraz ze względu na ochronę życia prywatnego lub inny ważny interes prywatny, a szczególnie dobro małoletniego pokrzywdzonego przestępstwem, należy odstąpić od podania na wokandzie imion i nazwisk stron lub uczestników postępowania nieprocesowego lub wezwanych osób. W takiej sytuacji podaje się wyłącznie ich inicjały lub nie zamieszcza się żadnych danych identyfikujących te osoby.
§ 71. O ograniczeniu treści wokandy, stosownie do § 70 ust. 4, decyduje przewodniczący wydziału, przewodniczący posiedzenia lub rozprawy, sędzia lub asesor sądowy, któremu przydzielono daną sprawę.
§ 72. 1. Do wokand z posiedzeń niejawnych w sprawach cywilnych nie stosuje się § 69 ust. 2-3, § 70 i § 71.
2. Wokanda z posiedzenia niejawnego w sprawie cywilnej zawiera: imiona i nazwiska sędziów, asesorów sądowych i ławników oraz sygnatury akt spraw wyznaczonych do rozpoznania.

Zawartość zmiennych w pliku config.php – każda linia musi kończyć się średnikiem!

ini_set(‚display_errors’,0);	
Czy wokanda ma wyświetlać błędy w przypadku wystąpienia błędu? Ustaw na 0, chyba, że są jakieś problemy i wymagane jest zebranie błędów dla programisty.	0 lub 1

error_reporting(0); Czy zapisywać błędy w pliku log? Ustaw na 0, chyba, że są jakieś problemy i wymagane jest zebranie błędów dla programisty.	error_reporting(0); lub error_reporting(E_ALL);

$jaki_sad	Nazwa sądu do wyświetlenia na wokandzie	$jaki_sad = ‚Nazwa sądu’;

$czykarny	Czy pobierać wokandę dla wydziału karnego?	$czykarny=true; lub $czykarny=false; 

$czyrodzinny	Czy pobierać wokandę dla wydziału rodzinnego?	$czyrodzinny=true; lub $czyrodzinny=false;

$czycywilny	Czy pobierać wokandę dla wydziału cywilnego?	$czycywilny=true; lub $czycywilny=false;

$czyzbiorcza	Czy wyświetlać wokandę zbiorczą?	$czyzbiorcza=true;

$temida_image	Obrazek wyświetlany na wokandzie w przypadku braku wokand na dzień.	‚img/temida2.png’; ‚img/temida3.jpg’;‚img/temida4.png’; lub można wgrać w do katalogu img dowolny obrazek lub też podać link zewnętrzny.

$defaultrowperpagespge	Domyślna ilość wierszy (rekordów) na wokandzie wspólna. Zalecane 4, gdyż w dalszej części można dobierać indywidualnie dla każdego wydziału.	Zalecane 4

$odswiezanie_zbiorcza	Interwał odświeżania wokandy zbiorczeji jednocześnie POBIERANIA DANYCH Z SAWY dla innych wokand. Czas podawany w milisekundach	500 lub w zależności od upodobań.

$odswiezanie	Interwał pobierania danych z bazy wokanda, tabela wokanda. Nie należy wstawiać zbyt małej wartości, ponieważ może to zaburzyć pracę paginacji wokandy.	240 lub w zależności od upodobań.

$defaultrowperpagespge_kar	Ilość rekordów (pozycji sesji w jednej paginacji) na wokandzie karnej.	4 lub 5 lub więcej – należy dobrać tak, by mieściło się na wokandzie. Lepiej dać mniej, ponieważ w przypadku większej ilości stron, może nie mieścić się na wokandzie. 

$defaultrowperpagespge_cyw	Ilość rekordów (pozycji sesji w jednej paginacji) na wokandzie cywilnej.	3 lub 4 – należy dobrać tak, by wszystko mieściło się na wokandzie. Lepiej dać mniej, ponieważ w przypadku większej ilości stron, co ma miejsce w wydziale cywilnym, zawartość może nie mieścić się na wokandzie.

$defaultrowperpagespge_rodz	Ilość rekordów (pozycji sesji w jednej paginacji) na wokandzie rodzinny.	4 lub 5 lub więcej – należy dobrać tak, by mieściło się na wokandzie. Lepiej dać mniej, ponieważ w przypadku większej ilości stron, może nie mieścić się na wokandzie.

$karny_przedmiot	Czy wyświetlać przedmiot rozprawy na wokandzie karnej?	Zalecane false, ze względu na ochronę danych.

$karny_kwalifikacja	Czy wyświetlać kwalifikację na pozycji wokandy karnej?	Zalecane false, ze względu na ochronę danych.

$karny_status_stron	Czy wyświetlać status stron na wokandzie karnej?	Zalecane false, ze względu na ochronę danych.

$czyobowiazek_karny	Czy wyświetlać (obow.) na wokandzie karnej?	Zalecane false, chyba, że osoby decyzyjne zdecydują inaczej.

$czyobowiazek_rodzinny	Czy wyświetlać (obow.) na wokandzie rodzinnej?	Zalecane false, chyba, że osoby decyzyjne zdecydują inaczej.

$rodzinny_przedmiot	Czy wyświetlać przedmiot rozprawy na wokandzie rodzinnej?	Zalecane false, ze względu na ochronę danych.

$rodzinny_kwalifikacja		

$rodzinny_status_stron	Czy wyświetlać status stron na wokandzie rodzinnej?	Zalecane false, ze względu na ochronę danych.

$czyobowiazek_cywilny		

$cywilny_przedmiot	Czy wyświetlać przedmiot rozprawy na wokandzie cywilnej?	Zalecane false, ze względu na ochronę danych.

$cywilny_kwalifikacja		

$cywilny_status_stron	Czy wyświetlać status stron na wokandzie cywilnej?	Zalecane false, ze względu na ochronę danych.

$baza_wokanda_pass	Hasło do bazy wokanda	Hasło nadane w MS SQL dla użytkownika

$baza_wokanda_user	Użytkownik bazy wokanda	Użytkownik, który ma dostęp do bazy wokanda

$baza_wokanda_baza	Baza danych danych z integratora	Domyślnie USE WOKANDA (chyba że inna nazwa bazy)

$baza_wokanda	String połączenia z bazą danych wokanda	‚DRIVER={SQL Server}; SERVER=ipserwerainstancja, 1433; DATABASE=wokanda’;

$karny_ip_server	IP serwera wydziału karnego	x.x.x.x

$baza_karny_pass	Hasło do bazy systemu wydziałowego SAWA – karny	Hasło

$baza_karny_user	Użytkownik bazy systemu wydziałowego SAWA – karny	Użytkownik mający dostęp (wystarczy do odczytu)

$baza_karny_baza	Baz danych danych SAWA – karny	USE KARNY

$baza_karny	String połączenia do bazy wydziałowej SAWA – karny.	‚DRIVER={SQL Server}; SERVER=ipserwerainstancja, 1433; DATABASE=karny’;

$baza_rodzinny_pass	Hasło do bazy systemu wydziałowego SAWA – rodzinny	Hasło

$baza_rodzinny_user	Użytkownik bazy systemu wydziałowego SAWA – rodzinny	Użytkownik mający dostęp (wystarczy do odczytu)

$baza_rodzinny_baza	Baz danych danych SAWA – rodzinny	USE RODZINNY

$baza_rodzinny	String połączenia do bazy wydziałowej SAWA – rodzinny.	‚DRIVER={SQL Server}; SERVER=ipserwerainstancja, 1433; DATABASE=rodzinny’;

$baza_cywilny_pass	Hasło do bazy systemu wydziałowego SAWA – cywilny	Hasło

$baza_cywilny_user	Użytkownik bazy systemu wydziałowego SAWA – cywilny	Użytkownik mający dostęp (wystarczy do odczytu)

$baza_cywilny_baza	Baz danych danych SAWA – cywilny	USE CYWILNY

$baza_cywilny	String połączenia do bazy wydziałowej SAWA – cywilny.	‚DRIVER={SQL Server}; SERVER=ipserwerainstancja, 1433; DATABASE=cywilny’;

Po wdrożeniu system działa bezawaryjnie od wielu lat. 

Leszek Klich
tel. 691050123
