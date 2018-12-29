Tworzenie widoków w bazach Currendy
Uwaga - utworzyć na każdej bazie, z której wyświeltać będziemy wokandy.
Jeśli któryć widok nie chce się utworzyć, należy zmienić kolejność ich tworzenia - najpierw słowniki, potem widoki, które się do nich odnoszą.
Zauważyłem, że w niektórych sądach należy nieco zmodyfikować widoki. To są widoki do wydziału karnego.
 

nazwa widoku: lkwok_instytucje
----------------------------------
SELECT ident, d_modyfikacji, nazwa AS instytucja, czyus
FROM dbo.Instytucja AS instytucja
----------------------------------

nazwa widoku: lkwok_obronca
----------------------------------
SELECT ident, d_modyfikacji, imie, nazwisko, CASE obronca.tytul WHEN 0 THEN 'adw.' WHEN 1 THEN 'adw.' WHEN 2 THEN 'r. pr.' ELSE NULL END AS tytul, czyus
FROM dbo.obronca AS obronca
----------------------------------

nazwa widoku: lkwok_obroncy
----------------------------------
SELECT broni.ident, broni.d_modyfikacji, broni.obowiazek, broni.urzwyb AS czy_wybor, status.nazwa AS status, COALESCE (ekspertyza.czywokanda, broni.czywokanda) AS czy_wokanda, strona.ident AS id_strony, 
       ekspertyza.ident AS id_innej_strony, broni.id_sprawy, broni.id_obroncy, broni.czyus, broni.czywokanda
FROM dbo.broni AS broni LEFT OUTER JOIN
  dbo.status AS status ON status.ident = broni.id_statusu LEFT OUTER JOIN
  dbo.ekspertyza AS ekspertyza ON ekspertyza.ident = broni.id_ekspertyzy AND ekspertyza.id_innej IS NOT NULL LEFT OUTER JOIN
  dbo.strona AS strona ON strona.ident = broni.id_strony AND strona.id_danych IS NOT NULL
WHERE (strona.ident IS NOT NULL) AND (strona.id_sprawy IS NOT NULL) OR
 (ekspertyza.ident IS NOT NULL) AND (ekspertyza.id_sprawy IS NOT NULL)
----------------------------------

nazwa widoku: lkwok_obroncy_dzis
----------------------------------
SELECT DISTINCT dbo.lkwok_sprawy.ident AS id_sprawy, dbo.lkwok_obroncy.status, dbo.lkwok_obroncy.czy_wokanda, dbo.lkwok_obroncy.obowiazek, dbo.lkwok_obronca.tytul, RTRIM(dbo.lkwok_obronca.imie) 
 + ' ' + RTRIM(dbo.lkwok_obronca.nazwisko) AS obronca, RTRIM(dbo.lkwok_sprawy.signature) AS sygnatura, dbo.lkwok_obroncy.czy_wybor, dbo.lkwok_obroncy.czyus, dbo.lkwok_obroncy.czywokanda
FROM dbo.lkwok_sprawy INNER JOIN
 dbo.lkwok_obroncy ON dbo.lkwok_sprawy.ident = dbo.lkwok_obroncy.id_sprawy INNER JOIN
 dbo.lkwok_obronca ON dbo.lkwok_obroncy.id_obroncy = dbo.lkwok_obronca.ident
WHERE (dbo.lkwok_obroncy.czy_wokanda = 1) AND (dbo.lkwok_obroncy.czyus = 0) AND (dbo.lkwok_obronca.czyus = 0)
----------------------------------

nazwa widoku: lkwok_oskarzyciel
----------------------------------
SELECT oskarzyciel.ident, oskarzyciel.d_modyfikacji, oskarzyciel.id_sprawy, oskarzyciel.id_instytucji, oskarzyciel.czyus, RTRIM(dbo.repertorium.symbol) + ' ' + RTRIM(oskarzyciel.numer) + '/' + RTRIM(oskarzyciel.rok) 
  AS rep_oskarzyciela FROM dbo.oskarzyciel AS oskarzyciel INNER JOIN
    dbo.repertorium ON oskarzyciel.repertorium = dbo.repertorium.numer
WHERE (oskarzyciel.czysyg IS NOT NULL)
----------------------------------

nazwa widoku: lkwok_pracownicy
----------------------------------
SELECT pracownik.ident, pracownik.d_modyfikacji, pracownik.imie, pracownik.nazwisko, pracownik.czyus, dbo.tytuly.nazwa
FROM dbo.pracownik AS pracownik INNER JOIN dbo.tytuly ON pracownik.tytul = dbo.tytuly.numer
----------------------------------

nazwa widoku: lkwok_prokuratorzy
----------------------------------
SELECT ident, d_modyfikacji, imie, nazwisko, czyus
FROM dbo.prokurator AS prokurator
----------------------------------

nazwa widoku: lkwok_repertorium
----------------------------------
SELECT numer AS ident, NULL AS d_modyfikacji, symbol, rodzaj
FROM dbo.repertorium AS repertorium
WHERE (ident = (SELECT MAX(ident) AS Expr1 FROM dbo.repertorium AS r
WHERE (numer = repertorium.numer) AND (rodzaj = 0)))
----------------------------------

nazwa widoku: lkwok_rozprawa
----------------------------------
SELECT rozprawa.ident, rozprawa.d_modyfikacji, rozprawa.d_rozprawy, rozprawa.d_rozprawy_do, rozprawa.id_typu, rozprawa.czyus, rozprawa.czyjawne, COALESCE (rozprawa.przedmiot,
 (SELECT przedmiot FROM dbo.sprawa AS sprawa WHERE (ident = rozprawa.id_sprawy)),
 (SELECT nazwa FROM dbo.przedmiot AS przedmiot WHERE (ident = sprawa.id_przedmiot))) AS przedmiot, rozprawa.opis, rozprawa.id_sprawy, rozprawa.id_terminu, rozprawa.rodzaj, rozprawa.czykoniec, rozprawa.iles, rozprawa.ilet, rozprawa.ileb, rozprawa.ilestr, 
  rozprawa.czynagranie, rozprawa.swor_opis FROM dbo.rozprawa AS rozprawa INNER JOIN
  dbo.sprawa AS sprawa ON sprawa.ident = rozprawa.id_sprawy
----------------------------------

nazwa widoku: lkwok_rozprawa_typ
----------------------------------
SELECT ident, d_modyfikacji, nazwa, 'FINISHED' AS type, czyus
FROM dbo.typ AS typ_rozprawy
----------------------------------

nazwa widoku: lkwok_sedzia_pomocnik
----------------------------------
SELECT sadzi.ident, sadzi.d_modyfikacji, sadzi.id_terminu, sadzi.id_sedziego, sadzi.czyus, '' AS nazwa_sedziego
FROM dbo.sadzi AS sadzi INNER JOIN
  dbo.sedzia AS sedzia ON sedzia.ident = sadzi.id_sedziego
WHERE (sadzi.czyprzewodniczy = 0) AND (sedzia.czylawnik = 0)
----------------------------------

nazwa widoku: lkwok_sedzia_tytul
----------------------------------
SELECT 0 AS ident, 'SSA' AS tytul
UNION ALL
SELECT 1 AS ident, 'SSO' AS tytul
UNION ALL
SELECT 2 AS ident, 'SSO del.' AS tytul
UNION ALL
SELECT 3 AS ident, 'SSR' AS tytul
UNION ALL
SELECT 4 AS ident, 'SSR del.' AS tytul
UNION ALL
SELECT 5 AS ident, 'p.o. SSA' AS tytul
UNION ALL
SELECT 6 AS ident, 'p.o. SSO' AS tytul
UNION ALL
SELECT 7 AS ident, 'As. SR' AS tytul
----------------------------------

nazwa widoku: lkwok_sedziowie
----------------------------------
SELECT ident, d_modyfikacji, imie, nazwisko, tytul, CASE WHEN sedzia.czyus = 1 OR
 sedzia.czyskreslono = 1 THEN 1 ELSE 0 END AS czyus
FROM dbo.sedzia AS sedzia
----------------------------------

nazwa widoku: lkwok_sprawy
----------------------------------
SELECT sprawa.ident, sprawa.id_prokurator, sprawa.d_modyfikacji, sprawa.czyus, COALESCE (sprawa.przedmiot,
  (SELECT nazwa FROM dbo.przedmiot AS przedmiot WHERE (ident = sprawa.id_przedmiot))) AS przedmiot, sprawa.uwagi, sprawa.id_sedziego,
  (SELECT TOP (1) repertorium.numer FROM dbo.sprawa AS s INNER JOIN
  dbo.repertorium AS repertorium ON repertorium.numer = s.repertorium
  WHERE (s.ident = sprawa.ident) AND (repertorium.rodzaj = 0)
   ORDER BY s.ident DESC) AS id_repertorium, COALESCE
     ((SELECT TOP (1) czyapelacja FROM dbo.broni WHERE (id_sprawy = sprawa.ident) AND (czyapelacja = 1)), 0) AS czyapelacja, instytucja_so.nazwa AS instytucja_so,
      (SELECT TOP (1) instytucja_osk.nazwa FROM dbo.oskarzyciel AS oskarzyciel INNER JOIN
   dbo.repertorium AS repertorium_osk ON repertorium_osk.numer = oskarzyciel.repertorium INNER JOIN
   dbo.Instytucja AS instytucja_osk ON instytucja_osk.ident = oskarzyciel.id_instytucji
   WHERE (oskarzyciel.id_sprawy = sprawa.ident) AND (repertorium_osk.rodzaj = 4)
     ORDER BY oskarzyciel.ident DESC) AS instytucja_osk, NULL AS id_lawnik1, NULL AS id_lawnik2,
     (SELECT TOP (1) (SELECT LTRIM(RTRIM(oznaczenie)) AS Expr1
    FROM dbo.konfig) + ' ' + COALESCE (LTRIM(RTRIM(repertorium.symbol)), '') + ' ' + CAST(sprawa.numer AS VARCHAR) + '/' + SUBSTRING(CAST(sprawa.rok AS VARCHAR(4)), 3, 2) AS Expr1
    FROM dbo.sprawa AS s INNER JOIN
      dbo.repertorium AS repertorium ON repertorium.numer = s.repertorium
      WHERE (s.ident = sprawa.ident) AND (repertorium.rodzaj = 0)
      ORDER BY s.ident DESC) AS signature, COALESCE (sprawa.sygnatura_so,
      (SELECT TOP (1) CAST(sprawa.oznaczenie_so AS VARCHAR) + ' ' + COALESCE (LTRIM(RTRIM(repertorium.symbol)), '') + ' ' + CAST(s.numer_so AS VARCHAR) + '/' + SUBSTRING(CAST(s.rok_so AS VARCHAR(4)), 3, 2) AS Expr1
        FROM dbo.sprawa AS s INNER JOIN
          dbo.repertorium AS repertorium ON repertorium.numer = s.repertorium_so
          WHERE (s.ident = sprawa.ident)
            ORDER BY s.ident DESC)) AS signature_so,
             (SELECT TOP (1) COALESCE (oskarzyciel.sygnatura, CAST(oskarzyciel.oznaczenie AS VARCHAR) + ' ' + COALESCE (LTRIM(RTRIM(repertorium_osk.symbol)), '') + ' ' + CAST(oskarzyciel.numer AS VARCHAR) 
               + '/' + SUBSTRING(CAST(oskarzyciel.rok AS VARCHAR(4)), 3, 2)) AS Expr1
               FROM dbo.oskarzyciel AS oskarzyciel INNER JOIN
                    dbo.repertorium AS repertorium_osk ON repertorium_osk.numer = oskarzyciel.repertorium
                     WHERE (oskarzyciel.id_sprawy = sprawa.ident) AND (repertorium_osk.rodzaj = 4)
                       ORDER BY oskarzyciel.ident DESC) AS signature_osk
FROM dbo.sprawa AS sprawa LEFT OUTER JOIN
  dbo.Instytucja AS instytucja_so ON instytucja_so.ident = sprawa.id_so
----------------------------------

nazwa widoku: lkwok_strony
----------------------------------
SELECT strona.ident, strona.d_modyfikacji, dane_strony.ident AS id_danych, strona.id_sprawy, strona.czywokanda AS czy_wokanda, status.nazwa AS status, strona.uwagi, strona.ao_kwalifikacja AS kwalifikacja, strona.czyus
FROM dbo.strona AS strona LEFT OUTER JOIN
   dbo.status AS status ON strona.id_statusu = status.ident INNER JOIN
   dbo.dane_strony AS dane_strony ON dane_strony.ident = strona.id_danych
----------------------------------

nazwa widoku: lkwok_strony_dane
----------------------------------
SELECT ident, d_modyfikacji, imie, nazwisko, czyus, czymaloletni
FROM dbo.dane_strony AS dane_strony
----------------------------------

nazwa widoku: lkwok_
----------------------------------
----------------------------------

nazwa widoku: lkwok_strony_dzis
----------------------------------
SELECT dbo.lkwok_rozprawa.d_rozprawy, dbo.lkwok_termin.sala, RTRIM(dbo.lkwok_strony.status) AS status_strony, RTRIM(dbo.lkwok_strony_dane.imie) + ' ' + RTRIM(dbo.lkwok_strony_dane.nazwisko) AS osoba, 
  RTRIM(dbo.lkwok_strony_dane.imie) AS imie, RTRIM(dbo.lkwok_strony_dane.nazwisko) AS nazwisko, dbo.lkwok_strony.kwalifikacja, dbo.lkwok_termin.g_pocz, dbo.lkwok_termin.g_kon, 
  dbo.lkwok_termin.d_posiedz AS data_rozprawy, dbo.lkwok_sprawy.uwagi, dbo.lkwok_termin.ident AS id_terminu, dbo.lkwok_rozprawa.ident AS id_rozprawy, RTRIM(dbo.lkwok_sprawy.signature) AS sygnatura, 
  dbo.lkwok_strony_dane.ident AS id_strony
FROM dbo.lkwok_rozprawa INNER JOIN
   dbo.lkwok_termin ON dbo.lkwok_rozprawa.id_terminu = dbo.lkwok_termin.ident INNER JOIN
   dbo.lkwok_sprawy ON dbo.lkwok_rozprawa.id_sprawy = dbo.lkwok_sprawy.ident INNER JOIN
   dbo.lkwok_strony ON dbo.lkwok_sprawy.ident = dbo.lkwok_strony.id_sprawy INNER JOIN
   dbo.lkwok_strony_dane ON dbo.lkwok_strony.id_danych = dbo.lkwok_strony_dane.ident
WHERE (dbo.lkwok_rozprawa.d_rozprawy BETWEEN CONVERT(CHAR(10), GETDATE(), 120) + ' 00:00:00' AND CONVERT(CHAR(10), GETDATE(), 120) + ' 23:59:00')
----------------------------------

nazwa widoku: lkwok_strony_inne
----------------------------------
SELECT TOP (100) PERCENT ekspertyza.ident, ekspertyza.d_modyfikacji, ekspertyza.id_innej AS id_danych_innej_strony, ekspertyza.id_sprawy, ekspertyza.czywokanda AS czy_wokanda, status.nazwa AS status, NULL AS kwalifikacja, 
   ekspertyza.czyus, ekspertyza.id_statusu, ekspertyza.obowiazek, ekspertyza.d_rozprawy, ekspertyza.d_kreacji
FROM dbo.ekspertyza AS ekspertyza LEFT OUTER JOIN
   dbo.status AS status ON ekspertyza.id_statusu = status.ident
WHERE (ekspertyza.id_innej IS NOT NULL) AND (ekspertyza.czywokanda = 1)
----------------------------------

nazwa widoku: lkwok_strony_inne_dane
----------------------------------
SELECT ident, d_modyfikacji, imie, nazwisko, 
  CASE inna_strona.tytul WHEN 0 THEN 'lek. med.' WHEN 1 THEN 'dr med.' WHEN 2 THEN 'dr n. med.' WHEN 3 THEN 'mgr' WHEN 4 THEN 'mgr inż.' WHEN 5 THEN 'dr' WHEN 6 THEN 'dr inż.' WHEN 7 THEN 'dr hab.' ELSE NULL
  END AS tytul, id_instytucji, czyus
FROM dbo.inna_strona AS inna_strona
----------------------------------

nazwa widoku: lkwok_strony_inne_dzis
----------------------------------
SELECT dbo.lkwok_sprawy.signature, RTRIM(dbo.lkwok_strony_inne_dane.imie) + ' ' + RTRIM(dbo.lkwok_strony_inne_dane.nazwisko) AS nazwa_strony, dbo.lkwok_instytucje.instytucja, dbo.lkwok_sprawy.ident AS id_sprawy, 
 dbo.lkwok_strony_inne.status AS status_strony, dbo.lkwok_strony_inne.obowiazek, dbo.lkwok_strony_inne.czy_wokanda, dbo.lkwok_strony_inne_dane.ident AS id_strony, dbo.lkwok_strony_inne_dane.imie, 
 dbo.lkwok_strony_inne_dane.nazwisko
FROM dbo.lkwok_strony_inne INNER JOIN
   dbo.lkwok_sprawy ON dbo.lkwok_strony_inne.id_sprawy = dbo.lkwok_sprawy.ident INNER JOIN
   dbo.lkwok_strony_inne_dane ON dbo.lkwok_strony_inne.id_danych_innej_strony = dbo.lkwok_strony_inne_dane.ident LEFT OUTER JOIN
   dbo.lkwok_instytucje ON dbo.lkwok_strony_inne_dane.id_instytucji = dbo.lkwok_instytucje.ident
WHERE (dbo.lkwok_strony_inne.czy_wokanda = 1)
----------------------------------

nazwa widoku: lkwok_strony_inne_obecnosc
----------------------------------
SELECT obecnosc.ident, obecnosc.d_rozprawy AS d_modyfikacji, obecnosc.id_rozprawy, obecnosc.d_rozprawy, obecnosc.czyus, obecnosc.obowiazek, ekspertyza.id_sprawy, ekspertyza.czywokanda, ekspertyza.id_innej
FROM dbo.rozprawa AS rozprawa INNER JOIN
  dbo.broni AS broni ON rozprawa.id_sprawy = broni.id_sprawy INNER JOIN
  dbo.ekspertyza AS ekspertyza ON ekspertyza.ident = broni.id_ekspertyzy RIGHT OUTER JOIN
  dbo.obecnosc AS obecnosc ON ekspertyza.id_innej IS NOT NULL AND broni.ident = obecnosc.id_broni
WHERE (obecnosc.czywokanda = 1)
GROUP BY obecnosc.ident, obecnosc.d_rozprawy, obecnosc.id_rozprawy, obecnosc.czyus, obecnosc.obowiazek, ekspertyza.id_sprawy, ekspertyza.czywokanda, ekspertyza.id_innej
HAVING (obecnosc.czyus = 0)
----------------------------------

nazwa widoku: lkwok_termin
----------------------------------
SELECT ident, d_modyfikacji, lp, sala, rodzaj, id_protokol, id_prokur,
  (SELECT DISTINCT id_sedziego FROM dbo.sadzi AS sadzi WHERE 
     (ident = (SELECT TOP (1) ident FROM dbo.sadzi AS s WHERE (id_terminu = termin.ident) AND (czyprzewodniczy = 1) AND (czyus = 0) AND EXISTS
        (SELECT 1 AS Expr1 FROM dbo.sedzia AS sedzia WHERE (ident = s.id_sedziego))
          ORDER BY ident DESC))) AS id_sedziego, czyus, RTRIM(CONVERT(VARCHAR(10), d_posiedz, 120) + ' ' + CONVERT(VARCHAR(8), COALESCE (g_pocz, '2000-01-01 07:00:00.000'), 108)) AS g_pocz, 
           RTRIM(CONVERT(VARCHAR(10), d_posiedz, 120) + ' ' + CONVERT(VARCHAR(8), COALESCE (g_kon, '2000-01-01 18:00:00.000'), 108)) AS g_kon, d_posiedz FROM dbo.termin AS termin
----------------------------------

nazwa widoku: lkwok_Wokanda_dzis
----------------------------------
SELECT TOP (100) PERCENT dbo.lkwok_sprawy.signature AS sygnatura_sprawy, RTRIM(dbo.lkwok_termin.sala) AS sala, dbo.lkwok_termin.rodzaj, dbo.lkwok_termin.g_pocz AS godzina_poczatek, 
  dbo.lkwok_termin.g_kon AS godzina_koniec, dbo.lkwok_rozprawa.czyjawne, dbo.lkwok_rozprawa.d_rozprawy, dbo.lkwok_rozprawa.d_rozprawy_do, RTRIM(dbo.lkwok_prokuratorzy.imie) 
  + ' ' + RTRIM(dbo.lkwok_prokuratorzy.nazwisko) AS Prokurator, RTRIM(dbo.lkwok_sprawy.instytucja_osk) AS oskarzyciel, dbo.lkwok_sprawy.przedmiot AS o_czym, RTRIM(dbo.lkwok_rozprawa_typ.nazwa) AS wynik, 
  CASE (dbo.lkwok_rozprawa.rodzaj) WHEN 1 THEN 'Rozprawa' WHEN 2 THEN 'Posiedzenie jawne' WHEN 3 THEN 'Rozprawa' END AS tryb_rozprawy, RTRIM(dbo.lkwok_pracownicy.nazwa) 
  + ' ' + RTRIM(dbo.lkwok_pracownicy.imie) + ' ' + RTRIM(dbo.lkwok_pracownicy.nazwisko) AS protokolant, dbo.lkwok_sedzia_tytul.tytul + ' ' + RTRIM(dbo.lkwok_sedziowie.imie) + ' ' + RTRIM(dbo.lkwok_sedziowie.nazwisko) 
  AS sedzia, dbo.lkwok_oskarzyciel.rep_oskarzyciela, dbo.lkwok_rozprawa.ident AS id_rozprawy, dbo.lkwok_sprawy.ident AS id_sprawy, dbo.lkwok_rozprawa.czykoniec, dbo.lkwok_rozprawa.opis, 'karny' AS baza, 
  dbo.lkwok_rozprawa.iles, dbo.lkwok_rozprawa.ilet, dbo.lkwok_rozprawa.ileb, dbo.lkwok_rozprawa.ilestr
FROM dbo.lkwok_prokuratorzy RIGHT OUTER JOIN
  dbo.lkwok_termin INNER JOIN
  dbo.lkwok_rozprawa ON dbo.lkwok_termin.ident = dbo.lkwok_rozprawa.id_terminu INNER JOIN
  dbo.lkwok_sprawy ON dbo.lkwok_rozprawa.id_sprawy = dbo.lkwok_sprawy.ident INNER JOIN
  dbo.lkwok_pracownicy ON dbo.lkwok_termin.id_protokol = dbo.lkwok_pracownicy.ident INNER JOIN
  dbo.lkwok_sedzia_tytul INNER JOIN
  dbo.lkwok_sedziowie ON dbo.lkwok_sedzia_tytul.ident = dbo.lkwok_sedziowie.tytul ON dbo.lkwok_termin.id_sedziego = dbo.lkwok_sedziowie.ident LEFT OUTER JOIN
  dbo.lkwok_oskarzyciel ON dbo.lkwok_sprawy.ident = dbo.lkwok_oskarzyciel.id_sprawy ON dbo.lkwok_prokuratorzy.ident = dbo.lkwok_sprawy.id_prokurator LEFT OUTER JOIN
  dbo.lkwok_rozprawa_typ ON dbo.lkwok_rozprawa.id_typu = dbo.lkwok_rozprawa_typ.ident
WHERE (dbo.lkwok_rozprawa.d_rozprawy BETWEEN CONVERT(CHAR(10), GETDATE(), 120) + ' 00:00:00' AND CONVERT(CHAR(10), GETDATE(), 120) + ' 23:59:00')
ORDER BY dbo.lkwok_rozprawa.d_rozprawy
