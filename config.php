<?php
ini_set('display_errors', 1); // Czy wyświetlać błędy? DEBUGGING
error_reporting(E_ALL);       // error_reporting(E_ALL); error_reporting(0);

$jaki_sad = 'Sąd Rejonowy w Nisku'; // Nazwa twojego sądu
$datadata = date("Y-m-d");
$data_dzis = '\'' . $datadata . ' 02:00:00\' and \'' . $datadata . ' 23:59:00\''; // Na który dzień wokanda? (dziś)
$czykarny = true;
$czycywilny=false;
$czyrodzinny=true;
$czyzbiorcza=true;    // Tylko dla poprzednich = true!
$temida_image='img/temida4.png';

$karny_ip_server='0.0.0.0'; // Adres IP serwera baz danych SAWA - Karny
$cywilny_ip_server='0.0.0.0'; // Adres IP serwera baz danych SAWA - Cywilny
$rodzinny_ip_server='0.0.0.0'; // Adres IP serwera baz danych SAWA - Rodzinny

$defaultrowperpagespge = 4; // Domyślna ilość wierszy (rekordów) na wokandzie wspólna
$odswiezanie_zbiorcza=500; // Co ile sekund odświezać zbiorczą (zbiorcza realizuje pobieranie danych z wydziałów).
$odswiezanie=240;     // Co ile sekund odświezać (pobieranie danych z bazy wokanda na salach).
$defaultrowperpagespge_kar = 4; // Domyślna ilość wierszy (rekordów) na wokandzie KARNA
$defaultrowperpagespge_cyw = 4; // Domyślna ilość wierszy (rekordów) na wokandzie CYWILNA
$defaultrowperpagespge_rodz = 4; // Domyślna ilość wierszy (rekordów) na wokandzie RODZINNA
$defaulttimeperpage=13000;  // Czas na paginację stron wokandy w milisekundach

$karny_przedmiot=false; // Pokaz przedmiot na wokandzie karnej
$karny_kwalifikacja=false; // Pokaz kwalifikacje czynu na wokandzie karnej
$karny_status_stron=false; // Czy wyświetlać status stron karny?
$czyobowiazek_karny=false;  // Czy wyświetlać obowiązek (obow.) karny?
$czyobowiazek_rodzinny=false;  // Czy wyświetlać obowiązek (obow.) karny?
$karny_protokolant=true;  // Czy wyświetlać protokolantów na wokandzie karnej?

$rodzinny_przedmiot=false; // Pokaz przedmiot na wokandzie rodzinnej
$rodzinny_kwalifikacja=false; // Pokaz kwalifikacje czynu na wokandzie rodzinnej
$rodzinny_status_stron=false; // Czy wyświetlać status stron rodzinny?
$rodzinny_protokolant=true;  // Czy wyświetlać protokolantów na wokandzie rodzinnej?

$czyobowiazek_cywilny=false;  // Czy wyświetlać obowiązek (obow.) cywilnej?
$cywilny_przedmiot=false; // Pokaz przedmiot na wokandzie cywilnej
$cywilny_kwalifikacja=false; // Pokaz kwalifikacje czynu na wokandzie cywilnej
$cywilny_status_stron=false; // Czy wyświetlać status stron cywilny?
$cywilny_protokolant=true;  // Czy wyświetlać protokolantów na wokandzie cywilnej?

// Konfiguracja wokanda
$baza_wokanda_pass = 'xxxxxxx';      // Hasło bazy WOKANDA
$baza_wokanda_user = 'xxxxxxx';      // Uzytkownik bazy WOKANDA
$baza_wokanda_baza = 'USE wokanda';  // Wybor bazy danych WOKANDA
$baza_wokanda = 'DRIVER={SQL Server};SERVER=serwer;DATABASE=wokanda'; //Serwer MS SQL, na któerym jest baza wokanda
$wokanda_dsn = $baza_wokanda;
$conn_wokanda = odbc_connect($wokanda_dsn,$baza_wokanda_user,$baza_wokanda_pass) or die('Błąd ODBC : '.odbc_error().' :: '.odbc_errormsg().' :: '.$wokanda_dsn);
odbc_exec($conn_wokanda,$baza_wokanda_baza);

//if($czykarny == true) {
//if(pingAddress($karny_ip_server) { $czykarny=true; } else { $czykarny=false; } }

// Konfiguracja wydziału karnego
if ($czykarny==true){
$baza_karny_pass = 'xxxxxxx';   // Hasło bazy KARNY
$baza_karny_user = 'wokanda';         // Uytkownik bazy KARNY
$baza_karny_baza = 'USE karny';  // Wybr bazy danych KARNY
$baza_karny = 'DRIVER={SQL Server};SERVER=serwer;DATABASE=karny';
$karny_dsn = $baza_karny;
$conn_karny = odbc_connect($karny_dsn,$baza_karny_user,$baza_karny_pass) or die('Błąd ODBC : '.odbc_error().' :: '.odbc_errormsg().' :: '.$virtual_dsn);
odbc_exec($conn_karny,$baza_karny_baza);
$zapytanie_karny = 'select * from dbo.LKWOK_Wokanda_dzis';
try
  {
$result_karny = odbc_exec($conn_karny, $zapytanie_karny);
$zapytanie_konfig_karny= 'SELECT wydzial as nazwa_wydzialu FROM dbo.konfig';
$result_konfig_karny = odbc_exec($conn_karny, $zapytanie_konfig_karny);
  } catch (Exception $e) {}
}

//if($czycywilny == true) {
//if(pingAddress($cywilny_ip_server) { $czycywilny=true; } else { $czycywilny=false; } }

// Konfiguracja wydziału cywilnego
if ($czycywilny==true){
$baza_cywilny_pass = 'xxxxx';   // Hasło bazy
$baza_cywilny_user = 'wokanda';         // Użytkownik bazy
$baza_cywilny_baza = 'USE cywilny';  // Wybór bazy danych
$baza_cywilny = 'DRIVER={SQL Server};SERVER=db1;DATABASE=cywilny';
$cywilny_dsn = $baza_cywilny;
$conn_cywilny = odbc_connect($cywilny_dsn,$baza_cywilny_user,$baza_cywilny_pass) or die('Błąd ODBC : '.odbc_error().' :: '.odbc_errormsg().' :: '.$virtual_dsn);
odbc_exec($conn_cywilny,$baza_cywilny_baza);
$zapytanie_cywilny= 'select * from dbo.LKWOK_Wokanda_dzis';
try
  {
$result_cywilny = odbc_exec($conn_cywilny, $zapytanie_cywilny);
$zapytanie_konfig_cywilny= 'SELECT wydzial as nazwa_wydzialu FROM dbo.konfig';
$result_konfig_cywilny = odbc_exec($conn_cywilny, $zapytanie_konfig_cywilny);
  }  catch (Exception $e) {}
}

//if($czyrodzinny == true) {
//if(pingAddress($rodzinny_ip_server) { $czyrodzinny=true; } else { $czyrodzinny=false; } }


// Konfiguracja wydziału rodzinnego
if ($czyrodzinny==true){
$baza_rodzinny_pass = 'zaq12wsx';   // Hasło bazy
$baza_rodzinny_user = 'wokanda';         // Użytkownik bazy
$baza_rodzinny_baza = 'USE rodzinny';  // Wybr bazy danych
$baza_rodzinny = 'DRIVER={SQL Server};SERVER=db10;DATABASE=rodzinny';
$rodzinny_dsn = $baza_rodzinny;
$conn_rodzinny = odbc_connect($rodzinny_dsn,$baza_rodzinny_user,$baza_rodzinny_pass) or die('Błąd ODBC : '.odbc_error().' :: '.odbc_errormsg().' :: '.$virtual_dsn);
odbc_exec($conn_rodzinny,$baza_rodzinny_baza);
$zapytanie_rodzinny= 'select * from dbo.LKWOK_Wokanda_dzis';
try
  {
$result_rodzinny = odbc_exec($conn_rodzinny, $zapytanie_rodzinny);
$zapytanie_konfig_rodzinny= 'SELECT wydzial as nazwa_wydzialu FROM dbo.konfig';
$result_konfig_rodzinny = odbc_exec($conn_rodzinny, $zapytanie_konfig_rodzinny);
  }  catch (Exception $e) {}
}




?>
