
<?php
include('config.php');
//Skasowanie poprzednich i wypełnienie danymi WOKANDA
$zapytanie_wokanda= 'TRUNCATE TABLE wokanda';
$result_wokanda = odbc_exec($conn_wokanda, $zapytanie_wokanda);

// Karny - START - do bazy wokanda
if ($czykarny) {
$zapytanie_karny='SELECT * FROM LKWOK_Wokanda_dzis';
$result_karny = odbc_exec($conn_karny, $zapytanie_karny);
while(odbc_fetch_row($result_karny))
{
$numer_sali=null;
$data_rozprawy=null;
$godzina_start=null;
$godzina_stop=null;
$wydzial=null;
$dzien_tyg=null;
$pozycja=null;
$sygnatura=null;
$przewodniczacy=null;
$protokolant=null;
$prokurator=null;
$obroncy=null;
$oskarzeni=null;
$czy_maloletni=0;
$lawnicy=null;
$strony_inne=null;
$obowiazek=0;
$status_strony=null;
$wynik=null;
$sesja_poczatek=null;
$sesja_koniec=null;
$zakonczona=null;
$przedmiot=null;
$oskarzyciel=null;
$rep_oskarzyciela=null;
$tryb=null;
$kwalifikacja=null;
$jawna=1;

$numer_sali=Trim(addslashes(odbc_result($result_karny,"sala")));

$data_rozprawy=explode(" ",odbc_result($result_karny,"d_rozprawy"));
$sesja_poczatek=explode(" ",odbc_result($result_karny,"godzina_poczatek"));
$zakonczona=odbc_result($result_karny,"czykoniec");
$prokurator=odbc_result($result_karny,"prokurator");
$sygnatura=odbc_result($result_karny,"sygnatura_sprawy");
$wynik=odbc_result($result_karny,"wynik");
$wydzial=odbc_result($result_konfig_karny,"nazwa_wydzialu");
$przedmiot=odbc_result($result_karny,"o_czym");
$oskarzyciel=odbc_result($result_karny,"oskarzyciel");
$rep_oskarzyciela=odbc_result($result_karny,"rep_oskarzyciela");
$tryb=odbc_result($result_karny,"tryb_rozprawy");
$przewodniczacy=odbc_result($result_karny,"sedzia");
$protokolant=odbc_result($result_karny,"protokolant");
$godzina_start=date("H:i",strtotime(odbc_result($result_karny,"d_rozprawy")));
$godzina_stop=date("H:i",strtotime(odbc_result($result_karny,"d_rozprawy_do")));
$sesja_koniec=explode(" ",odbc_result($result_karny,"godzina_koniec"));
$jawna=odbc_result($result_karny,"czyjawne");
$wydz='kar';

// Kolumna Oskarżeni
$zapytanie_strony= 'SELECT * FROM lkwok_strony_dzis WHERE id_rozprawy = ' . odbc_result($result_karny,"id_rozprawy");
$result_strony = odbc_exec($conn_karny, $zapytanie_strony);
 while(odbc_fetch_row($result_strony)){	
	if($karny_status_stron==true) { $ststron='<i>' . trim(odbc_result($result_strony,"status_strony")) . '</i><b> '; } else { $ststron=null; }
	$oskarzeni = $oskarzeni . $ststron . trim(odbc_result($result_strony,"imie")) . ' ' . trim(odbc_result($result_strony,"nazwisko")) . '</b><br>';
	$kwalifikacja=odbc_result($result_strony,"kwalifikacja");
 }

 // Obrońca lub obrońcy - jeśli są
    $obroncy=null;
    $zapytanie_obroncy_dzis= 'SELECT * FROM lkwok_obroncy_dzis WHERE id_sprawy = ' . odbc_result($result_karny,"id_sprawy");
    $result_obroncy_dzis = odbc_exec($conn_karny, $zapytanie_obroncy_dzis);
  while(odbc_fetch_row($result_obroncy_dzis)){
	 $obroncy = $obroncy . '<i>' . trim(odbc_result($result_obroncy_dzis,"status")) . '</i> ' . trim(odbc_result($result_obroncy_dzis,"tytul")) . ' ' . trim(odbc_result($result_obroncy_dzis,"obronca")) . '<br>';
	 }

//Inne strony - świadkowie, pokrzywdzeni, biegli	
	$zapytanie_inne_strony= 'SELECT ekspertyza.numer, status.nazwa AS status_strony, inna_strona.imie, inna_strona.nazwisko, obecnosc.d_rozprawy, ekspertyza.obowiazek FROM ekspertyza' . 
    ' INNER JOIN inna_strona ON inna_strona.ident = ekspertyza.id_innej INNER JOIN obecnosc ON ekspertyza.ident=obecnosc.id_broni' .
    ' INNER JOIN status ON status.ident = ekspertyza.id_statusu WHERE (ekspertyza.id_sprawy =' . odbc_result($result_karny,"id_sprawy") . ') AND (ekspertyza.czyus=0) AND ' . 
    ' (isnull(obecnosc.czyus,0)=0) and (obecnosc.typ_osoby=2) and (isnull(obecnosc.czywokanda,0)=1) and (obecnosc.id_rozprawy =' . odbc_result($result_karny,"id_rozprawy") . ') and (status.rodzaj <> 11)';
    $result_inne_strony = odbc_exec($conn_karny, $zapytanie_inne_strony);
	$obowiazek_bycia='';
	while(odbc_fetch_row($result_inne_strony)) {
	 if($karny_status_stron==true) { $ststron='<i>' . trim(odbc_result($result_inne_strony,"status_strony")) . '</i><b> '; } else $ststron=null;
     if ((odbc_result($result_inne_strony,"obowiazek")==1) && ($czyobowiazek_karny==true)) { $obowiazek_bycia = '(obow.)'; }
	 if ((strlen(odbc_result($result_inne_strony,"d_rozprawy")) > 0) && (odbc_result($result_inne_strony,"d_rozprawy")!='00:00:00.0000000') && (odbc_result($result_inne_strony,"d_rozprawy")!='03:00:00.0000000')  ) {
		 $nagodzine = date("H:i",strtotime(odbc_result($result_inne_strony,"d_rozprawy"))); } else $nagodzine='';
	 $strony_inne = $strony_inne . $nagodzine . ' ' . $ststron . trim(odbc_result($result_inne_strony,"imie")) . ' ' . trim(odbc_result($result_inne_strony,"nazwisko")) . '</b> ' . $obowiazek_bycia . '<br>';
	}

// Znajdź godzinę zakończenia ostatniej sesji, jeśli wszystkie zakończone
$zapytanie_sesja_stop= 'select count(czykoniec) AS czykoniec from LKWOK_Wokanda_dzis where czykoniec=1 AND sala=' . '\'' . $numer_sali . '\'';
$result_sesja_stop = odbc_exec($conn_karny, $zapytanie_sesja_stop);
$zapytanie_sesja_koniec= 'select max(godzina_koniec) AS koniec_sesji from LKWOK_Wokanda_dzis where czykoniec=1 AND sala=' . '\'' . $numer_sali . '\'';
$result_sesja_koniec = odbc_exec($conn_karny, $zapytanie_sesja_koniec);
$sesja_koniec=date("H:i",strtotime(odbc_result($result_sesja_koniec,"koniec_sesji")));
if ($sesja_koniec == '01:00') $sesja_koniec=null;

//echo $zapytanie_wokanda . '<br>';
$zapytanie_wokanda= "INSERT INTO wokanda (numer_sali, data_rozprawy,sesja_poczatek, zakonczona, sygnatura, prokurator, wynik,wydzial,przedmiot, oskarzyciel,rep_oskarzyciela,tryb,przewodniczacy,protokolant,godzina_start,kwalifikacja,oskarzeni,obroncy,strony_inne,sesja_koniec,wydz, godzina_stop) VALUES (" .
    '\'' . $numer_sali . '\'' . ',' . '\'' . $data_rozprawy[0] . '\'' ."," .
       '\'' . $sesja_poczatek[1] . '\'' . ',' . $zakonczona . ',' . '\'' . $sygnatura . '\'' . ',' . '\'' . $prokurator . '\'' .
	   ',' . '\'' . $wynik . '\'' . ',' . '\'' . $wydzial . '\'' . ',' . '\'' . $przedmiot . '\'' . ',' . '\'' . $oskarzyciel . '\'' .
       ',' . '\'' . $rep_oskarzyciela . '\'' . ',' . '\'' . $tryb . '\'' . ',' . '\'' . $przewodniczacy . '\'' .
	   ',' . '\'' . $protokolant . '\'' . ',' . '\'' . $godzina_start . '\'' . ',' . '\'' . $kwalifikacja . '\'' .
	   ',' . '\'' . $oskarzeni . '\'' . ',' . '\'' . $obroncy . '\'' . ',' . '\'' . $strony_inne . '\'' .
	   ',' . '\'' . $sesja_koniec . '\'' . ',' . '\'' . $wydz . '\'' . ',' . '\'' . $godzina_stop . '\'' .
	    ")";
$result_wokanda = odbc_exec($conn_wokanda, $zapytanie_wokanda);
//echo '<br><hr><br>' . $zapytanie_wokanda;
}
} // Koniec - KARNA


// RODZINNY - START - do bazy wokanda
if ($czyrodzinny) {
while(odbc_fetch_row($result_rodzinny))
{
$numer_sali=null;
$data_rozprawy=null;
$godzina_start=null;
$godzina_stop=null;
$wydzial=null;
$dzien_tyg=null;
$pozycja=null;
$sygnatura=null;
$przewodniczacy=null;
$protokolant=null;
$prokurator=null;
$obroncy=null;
$oskarzeni=null;
$czy_maloletni=0;
$lawnicy=null;
$strony_inne=null;
$obowiazek=0;
$status_strony=null;
$wynik=null;
$sesja_poczatek=null;
$sesja_koniec=null;
$zakonczona=null;
$przedmiot=null;
$oskarzyciel=null;
$rep_oskarzyciela=null;
$tryb=null;
$kwalifikacja=null;
$jawna=1;
$wydz='rodz';

$numer_sali=Trim(addslashes(odbc_result($result_rodzinny,"sala")));
$data_rozprawy=explode(" ",odbc_result($result_rodzinny,"d_rozprawy"));
$sesja_poczatek=explode(" ",odbc_result($result_rodzinny,"godzina_poczatek"));
$zakonczona=odbc_result($result_rodzinny,"czykoniec");
$godzina_start=odbc_result($result_rodzinny,'godzina_poczatek');
$prokurator=odbc_result($result_rodzinny,"prokurator");
$sygnatura=odbc_result($result_rodzinny,"sygnatura_sprawy");
$wynik=odbc_result($result_rodzinny,"wynik");
$wydzial=odbc_result($result_konfig_rodzinny,"nazwa_wydzialu");
$przedmiot=odbc_result($result_rodzinny,"o_czym");
$oskarzyciel=odbc_result($result_rodzinny,"oskarzyciel");
$rep_oskarzyciela=odbc_result($result_rodzinny,"rep_oskarzyciela");
$tryb=odbc_result($result_rodzinny,"tryb_rozprawy");
$przewodniczacy=odbc_result($result_rodzinny,"sedzia");
$protokolant=odbc_result($result_rodzinny,"protokolant");
$godzina_start=date("H:i",strtotime(odbc_result($result_rodzinny,"d_rozprawy")));
$sesja_koniec=explode(" ",odbc_result($result_rodzinny,"godzina_koniec"));
$jawna=odbc_result($result_rodzinny,"czyjawne");

// Kolumna Strony wnioskodawca, uczestnik. RODZINNY STRONY DZIS
$zapytanie_strony= 'SELECT * FROM lkwok_strony_dzis WHERE id_rozprawy = ' . odbc_result($result_rodzinny,"id_rozprawy");
$result_strony = odbc_exec($conn_rodzinny, $zapytanie_strony);
$oskarzeni=null;
 while(odbc_fetch_row($result_strony)){
	if($rodzinny_status_stron==true) { $ststron='<i>' . trim(odbc_result($result_strony,"status_strony")) . '</i><b> '; } else { $ststron=null; }
	if (odbc_result($result_strony,"czy_maloletni")==1) { $status_maloletni='małoletni '; } else $status_maloletni='' ;
	if ((trim(odbc_result($result_strony,"status_strony"))=='wnioskodawca') || (trim(odbc_result($result_strony,"status_strony"))=='wnioskodawczyni')) {
	 $oskarzyciel = $oskarzyciel . $ststron . ' ' . odbc_result($result_strony,"imie") . ' ' . odbc_result($result_strony,"nazwisko") . '<br>'; }
	 else { $oskarzeni = $oskarzeni . $ststron . odbc_result($result_strony,"imie") . ' ' . odbc_result($result_strony,"nazwisko") . '<br>'; }
	$kwalifikacja=odbc_result($result_strony,"kwalifikacja");
 }

 // Obrońca lub obrońcy - jeśli są
    $obroncy=null;
    $zapytanie_obroncy_dzis= 'SELECT * FROM lkwok_obroncy_dzis WHERE id_sprawy = ' . odbc_result($result_rodzinny,"id_sprawy");
    $result_obroncy_dzis = odbc_exec($conn_rodzinny, $zapytanie_obroncy_dzis);
  while(odbc_fetch_row($result_obroncy_dzis)){
	 $obroncy = $obroncy . '<i>' . trim(odbc_result($result_obroncy_dzis,"status")) . ' ' . trim(odbc_result($result_obroncy_dzis,"tytul")) . '</i> ' . odbc_result($result_obroncy_dzis,"obronca") . '<br>';
	 }

//Inne strony - świadkowie, pokrzywdzeni, biegli
	$zapytanie_inne_strony= 'SELECT status.nazwa AS status_strony, inna_strona.imie, inna_strona.nazwisko, obecnosc.d_rozprawy, ekspertyza.obowiazek FROM ekspertyza' . 
    ' INNER JOIN inna_strona ON inna_strona.ident = ekspertyza.id_innej INNER JOIN obecnosc ON ekspertyza.ident=obecnosc.id_broni' .
    ' INNER JOIN status ON status.ident = ekspertyza.id_statusu WHERE (ekspertyza.id_sprawy =' . odbc_result($result_rodzinny,"id_sprawy") . ') AND (ekspertyza.czyus=0) AND ' . 
    ' (isnull(obecnosc.czyus,0)=0) and (obecnosc.typ_osoby=2) and (isnull(obecnosc.czywokanda,0)=1) and (obecnosc.id_rozprawy =' . odbc_result($result_rodzinny,"id_rozprawy") . ') and (status.rodzaj <> 11)';
    $result_inne_strony = odbc_exec($conn_rodzinny, $zapytanie_inne_strony);
	$obowiazek_bycia='';
	while(odbc_fetch_row($result_inne_strony)) {
     if($rodzinny_status_stron==true) { $ststron='<i>' . trim(odbc_result($result_inne_strony,"status_strony")) . '</i><b> '; } else { $ststron=null; }		
     if ((odbc_result($result_inne_strony,"obowiazek")==1) && ($czyobowiazek_rodzinny==true)) { $obowiazek_bycia = '(obow.)'; }
	 
	 if ((strlen(odbc_result($result_inne_strony,"d_rozprawy")) > 0) && (odbc_result($result_inne_strony,"d_rozprawy")!='00:00:00.0000000') && (odbc_result($result_inne_strony,"d_rozprawy")!='03:00:00.0000000')  ) {
		 $nagodzine = date("H:i",strtotime(odbc_result($result_inne_strony,"d_rozprawy"))); } else $nagodzine='';
	 	 $strony_inne = $strony_inne . $nagodzine . ' ' .$ststron .' '. trim(odbc_result($result_inne_strony,"imie")) . ' ' . trim(odbc_result($result_inne_strony,"nazwisko")) . ' ' . $obowiazek_bycia . '<br>';
	}

// Znajdź godzinę zakończenia ostatniej sesji, jeśli wszystkie zakończone
$zapytanie_sesja_stop= 'select count(czykoniec) AS czykoniec from LKWOK_Wokanda_dzis where czykoniec=1 AND sala=' . '\'' . $numer_sali . '\'';
$result_sesja_stop = odbc_exec($conn_rodzinny, $zapytanie_sesja_stop);
$zapytanie_sesja_koniec= 'select max(godzina_koniec) AS koniec_sesji from LKWOK_Wokanda_dzis where czykoniec=1 AND sala=' . '\'' . $numer_sali . '\'';
$result_sesja_koniec = odbc_exec($conn_rodzinny, $zapytanie_sesja_koniec);
$sesja_koniec=date("H:i",strtotime(odbc_result($result_sesja_koniec,"koniec_sesji")));
if ($sesja_koniec == '01:00') $sesja_koniec=null;

$zapytanie_wokanda= "INSERT INTO wokanda (numer_sali, data_rozprawy,sesja_poczatek, zakonczona, sygnatura, prokurator, wynik,wydzial,przedmiot, oskarzyciel,rep_oskarzyciela,tryb,przewodniczacy,protokolant,godzina_start,kwalifikacja,oskarzeni,obroncy,strony_inne,sesja_koniec,wydz) VALUES (" .
      '\'' . $numer_sali . '\'' . ',' . '\'' . $data_rozprawy[0] . '\'' ."," .
       '\'' . $sesja_poczatek[1] . '\'' . ',' . $zakonczona . ',' . '\'' . $sygnatura . '\'' . ',' . '\'' . $prokurator . '\'' .
	   ',' . '\'' . $wynik . '\'' . ',' . '\'' . $wydzial . '\'' . ',' . '\'' . $przedmiot . '\'' . ',' . '\'' . $oskarzyciel . '\'' .
       ',' . '\'' . $rep_oskarzyciela . '\'' . ',' . '\'' . $tryb . '\'' . ',' . '\'' . $przewodniczacy . '\'' .
	   ',' . '\'' . $protokolant . '\'' . ',' . '\'' . $godzina_start . '\'' . ',' . '\'' . $kwalifikacja . '\'' .
	   ',' . '\'' . $oskarzeni . '\'' . ',' . '\'' . $obroncy . '\'' . ',' . '\'' . $strony_inne . '\'' .
	   ',' . '\'' . $sesja_koniec . '\'' . ',' . '\'' . $wydz . '\'' .  ")";
$result_wokanda = odbc_exec($conn_wokanda, $zapytanie_wokanda);
}
} // Koniec - RODZINNY

// CYWILNY - START - do bazy wokanda
if ($czycywilny) {
$zapytanie_cywilny='SELECT * FROM LKWOK_Wokanda_dzis';
try
  {
$result_cywilny = odbc_exec($conn_cywilny, $zapytanie_cywilny);
 }  catch (Exception $e) {}
while(odbc_fetch_row($result_cywilny))
{
$numer_sali=null;
$data_rozprawy=null;
$godzina_start=null;
$godzina_stop=null;
$wydzial=null;
$dzien_tyg=null;
$pozycja=null;
$sygnatura=null;
$przewodniczacy=null;
$protokolant=null;
$prokurator=null;
$obroncy=null;
$oskarzeni=null;
$czy_maloletni=0;
$lawnicy=null;
$strony_inne=null;
$obowiazek=0;
$status_strony=null;
$wynik=null;
$sesja_poczatek=null;
$sesja_koniec=null;
$zakonczona=null;
$przedmiot=null;
$oskarzyciel=null;
$rep_oskarzyciela=null;
$tryb=null;
$kwalifikacja=null;
$jawna=1;
$wydz='cyw';

$numer_sali=addslashes(odbc_result($result_cywilny,"sala"));
$data_rozprawy=explode(" ",odbc_result($result_cywilny,"d_rozprawy"));
$sesja_poczatek=explode(" ",odbc_result($result_cywilny,"godzina_poczatek"));
$zakonczona=odbc_result($result_cywilny,"czykoniec");
$godzina_start=odbc_result($result_cywilny,'godzina_poczatek');
$sygnatura=odbc_result($result_cywilny,"sygnatura_sprawy");
$wynik=odbc_result($result_cywilny,"wynik");
$wydzial=odbc_result($result_konfig_cywilny,"nazwa_wydzialu");
$przedmiot=odbc_result($result_cywilny,"o_czym");
$oskarzyciel=odbc_result($result_cywilny,"oskarzyciel");
$tryb=odbc_result($result_cywilny,"tryb_rozprawy");
$przewodniczacy=odbc_result($result_cywilny,"sedzia");
$protokolant=odbc_result($result_cywilny,"protokolant");
$godzina_start=date("H:i",strtotime(odbc_result($result_cywilny,"d_rozprawy")));
$sesja_koniec=explode(" ",odbc_result($result_cywilny,"godzina_koniec"));
$jawna=odbc_result($result_cywilny,"czyjawne");

// Kolumna STRONY - KTO? 'oskarzeni'->strony
$zapytanie_strony= 'SELECT * FROM lkwok_strony_dzis WHERE id_rozprawy = ' . odbc_result($result_cywilny,"id_rozprawy");
$result_strony = odbc_exec($conn_cywilny, $zapytanie_strony);
$oskarzeni='';
$oskarzyciel='';
$strony='';
$licznik=0;

// Strona - start POWÓD, WNIOSKUJĄCY, etc.
while(odbc_fetch_row($result_strony)){
$tmp_jakistatus = iconv("Windows-1250", "UTF-8", trim(odbc_result($result_strony,"status_strony")));
if (($tmp_jakistatus=='wnioskodawczyni') || ($tmp_jakistatus=='wnioskodawca') || ($tmp_jakistatus=='wierzyciel') || ($tmp_jakistatus=='wierzycielka') || ($tmp_jakistatus=='powód')  || ($tmp_jakistatus=='powódka') || ($tmp_jakistatus=='składający') ) {
$oskarzyciel = $oskarzyciel . odbc_result($result_strony,"imie") . ' '. odbc_result($result_strony,"nazwisko") . '<br>'; } 
else
if (($tmp_jakistatus=='pozwany') || ($tmp_jakistatus=='pozwana') || ($tmp_jakistatus=='dłużnik')) {
	$oskarzeni = $oskarzeni . odbc_result($result_strony,"imie") . ' ' . odbc_result($result_strony,"nazwisko") . '<br>'; } 
else $strony = $strony . odbc_result($result_strony,"imie") . ' ' . odbc_result($result_strony,"nazwisko") . '<br>';
}

  //Obrońcy
    $obroncy=null;
    $zapytanie_obroncy_dzis= 'SELECT * FROM lkwok_obroncy_dzis WHERE id_sprawy = ' . odbc_result($result_cywilny,"id_sprawy");
    $result_obroncy_dzis = odbc_exec($conn_cywilny, $zapytanie_obroncy_dzis);
  while(odbc_fetch_row($result_obroncy_dzis)){
	 $obroncy = $obroncy . trim(odbc_result($result_obroncy_dzis,"status")) . ' ' . trim(odbc_result($result_obroncy_dzis,"tytul")) . ' ' . trim(odbc_result($result_obroncy_dzis,"obronca")) . '<br>';
	 }

 //Inne strony - świadkowie, pokrzywdzeni, biegli
	$strony_inne='';
	$zapytanie_inne_strony= 'SELECT status.nazwa AS status_strony, inna_strona.imie, inna_strona.nazwisko, obecnosc.d_rozprawy FROM ekspertyza' . 
    ' INNER JOIN inna_strona ON inna_strona.ident = ekspertyza.id_innej INNER JOIN obecnosc ON ekspertyza.ident=obecnosc.id_broni' .
    ' INNER JOIN status ON status.ident = ekspertyza.id_statusu WHERE (ekspertyza.id_sprawy =' . odbc_result($result_cywilny,"id_sprawy") . ') AND (ekspertyza.czyus=0) AND ' . 
    ' (isnull(obecnosc.czyus,0)=0) and (obecnosc.typ_osoby=2) and (isnull(obecnosc.czywokanda,0)=1) and (obecnosc.id_rozprawy =' . odbc_result($result_cywilny,"id_rozprawy") . ') and (status.rodzaj <> 11)';
   $result_inne_strony = odbc_exec($conn_cywilny, $zapytanie_inne_strony);   
	while(odbc_fetch_row($result_inne_strony)) { 
  if($cywilny_status_stron==true) { $ststron='<i>' . trim(odbc_result($result_inne_strony,"status_strony")) . '</i><b> '; } else { $ststron=null; }		
	$biegli=null;
		 if ((strlen(odbc_result($result_inne_strony,"d_rozprawy")) != null) && (strlen(odbc_result($result_inne_strony,"d_rozprawy")) > 0) && (odbc_result($result_inne_strony,"d_rozprawy")!='00:00:00.0000000') && (odbc_result($result_inne_strony,"d_rozprawy")!='03:00:00.0000000')  ) {
		 $nagodzine = date("H:i",strtotime(odbc_result($result_inne_strony,"d_rozprawy"))); } else $nagodzine='';
  	     $tmp_jakistatus = iconv("Windows-1250", "UTF-8", trim(odbc_result($result_inne_strony,"status_strony")));

		if (($tmp_jakistatus=='biegły') || ($tmp_jakistatus=='biegła')) {  // Biegli
			$biegli = $biegli . $nagodzine . ' ' . $ststron . ' ' . trim(odbc_result($result_inne_strony,"imie")) . ' ' . trim(odbc_result($result_inne_strony,"nazwisko")). '<br>';
		} else {
			$strony_inne = $strony_inne . $nagodzine . ' ' . trim(odbc_result($result_inne_strony,"status_strony")) . ' ' . trim(odbc_result($result_inne_strony,"imie")) . ' ' . trim(odbc_result($result_inne_strony,"nazwisko")). '<br>';
    	}
	}

// Znajdź godzinę zakończenia ostatniej sesji, jeśli wszystkie zakończone
$zapytanie_sesja_stop= 'select count(czykoniec) AS czykoniec from LKWOK_Wokanda_dzis where czykoniec=1 AND sala=' . $numer_sali;
$result_sesja_stop = odbc_exec($conn_cywilny, $zapytanie_sesja_stop);
$zapytanie_sesja_koniec= 'select max(godzina_koniec) AS koniec_sesji from LKWOK_Wokanda_dzis where czykoniec=1 AND sala=' . $numer_sali;
$result_sesja_koniec = odbc_exec($conn_cywilny, $zapytanie_sesja_koniec);
$sesja_koniec=date("H:i",strtotime(odbc_result($result_sesja_koniec,"koniec_sesji")));
if ($sesja_koniec == '01:00') $sesja_koniec=null;
$zapytanie_wokanda= "INSERT INTO wokanda (numer_sali, data_rozprawy,sesja_poczatek, zakonczona, sygnatura, prokurator, wynik,wydzial,przedmiot, oskarzyciel, rep_oskarzyciela,tryb,przewodniczacy,protokolant,godzina_start,kwalifikacja,oskarzeni,obroncy,strony_inne,sesja_koniec,biegli,wydz,strony) VALUES (" .
       $numer_sali . ',' . '\'' . $data_rozprawy[0] . '\'' ."," . '\'' . $sesja_poczatek[1] . '\'' . ',' . $zakonczona .
	   ',' . '\'' . $sygnatura . '\'' . ',' . '\'' . $prokurator . '\'' . ',' . '\'' . $wynik . '\'' . ',' . '\'' . $wydzial .
	   '\'' . ',' . '\'' . $przedmiot . '\'' . ',' . '\'' . $oskarzyciel . '\'' . ',' . '\'' . $rep_oskarzyciela . '\'' .
	   ',' . '\'' . $tryb . '\'' . ',' . '\'' . $przewodniczacy . '\'' . ',' . '\'' . $protokolant . '\'' . ',' . '\'' .
	   $godzina_start . '\'' . ',' . '\'' . $kwalifikacja . '\'' . ',' . '\'' . $oskarzeni . '\'' . ',' . '\'' . $obroncy . '\'' .
	   ',' . '\'' . $strony_inne . '\'' . ',' . '\'' . $sesja_koniec . '\'' . ',' . '\'' . $biegli . '\'' . ',' . '\'' . $wydz . '\'' .
	   ',' . '\'' . $strony . '\'' . ")";
$result_wokanda = odbc_exec($conn_wokanda, $zapytanie_wokanda);
} } // Koniec - CYWILNY

?>
