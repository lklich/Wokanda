<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
<?php include ('config.php'); ?>
<meta http-equiv="refresh" content="<?php echo $odswiezanie ?>"/>
<title>Wokanda Sądu Rejonowego</title>
<script type="text/javascript" src="js/date_time.js"></script>
</head>

<?php
$sala_rozp = $_GET['sala'];
if (empty($sala_rozp)) { echo 'Zdefiniuj salę rozpraw!!!'; }

//include ('get.php'); //<-- włączyć w zbiorczej!

$ile_spraw_strona=4;
$datadata = date("Y-m-d");
//$data_dzis = '\'' . $datadata . ' 02:00:00\' and \'' . '2016-03-09' . ' 23:59:00\''; // Na który dzień wokanda? (dziś)
$zapytanie_wokanda= 'Select * from wokanda where numer_sali=' . '\'' . $sala_rozp . '\'' . ' ORDER BY godzina_start ASC';
$result_wokanda = odbc_exec($conn_wokanda, $zapytanie_wokanda);
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr>
<td height="205px" style="vertical-align: top;"><div class="header header-sala" id="naglowek2">
<table class="header-top header-top-sala" border="0">
<tbody><tr><td style="vertical-align: bottom;"><div class="court-info-sala"> <?php echo $jaki_sad ?> </div>
<span class="header-pair-sala header-pair-value"><span id="mainForm:beforeRendered:day">
<?php
function dateV($format,$timestamp=null){
	$to_convert = array(
		'l'=>array('dat'=>'N','str'=>array('Poniedziałek','Wtorek','Środa','Czwartek','Piątek','Sobota','Niedziela')),
		'F'=>array('dat'=>'n','str'=>array('styczeń','luty','marzec','kwiecień','maj','czerwiec','lipiec','sierpień','wrzesień','październik','listopad','grudzień')),
		'f'=>array('dat'=>'n','str'=>array('stycznia','lutego','marca','kwietnia','maja','czerwca','lipca','sierpnia','września','października','listopada','grudnia'))
	);
	if ($pieces = preg_split('#[:/.\-, ]#', $format)){
		if ($timestamp === null) { $timestamp = time(); }
		foreach ($pieces as $datepart){
			if (array_key_exists($datepart,$to_convert)){
				$replace[] = $to_convert[$datepart]['str'][(date($to_convert[$datepart]['dat'],$timestamp)-1)];
			}else{
				$replace[] = date($datepart,$timestamp);
			}
		}
		$result = strtr($format,array_combine($pieces,$replace));
		return $result;
	}
}
echo dateV('l j f Y', strtotime(date('y-m-d')));
?>
</span></span>
</td>
<td class="header-page-switcher"><div style="padding-bottom: 2px;"></div>
<div class="hour hour-sala" style="line-height: 1;">
<body onload=display_ct(); >
<span id="date_time"></span>
<script type="text/javascript">window.onload = date_time('date_time');</script>
</div></td></tr></tbody></table>
<div class="header-ruler"></div><span id="mainForm:beforeRendered:room-people"><table class="header-bottom header-bottom-sala">
<tbody><tr><td style="vertical-align: bottom;"><div id="mainForm:beforeRendered:j_id28" class="hour hour-sala">Sala: <?php echo $sala_rozp; ?></div>

<?php
$ile_wierszy = odbc_num_rows($result_wokanda); // num_rows - 1 = ilość pobranych wierszy
   if ($ile_wierszy <> 0)
	 {
	$sesja_pocz=substr(odbc_result($result_wokanda,"sesja_poczatek"),0,5);
    $data_koniec_rozpraw = substr(odbc_result($result_wokanda,"sesja_poczatek"), 0,6);
	  $godzina_koniec_rozp = substr(odbc_result($result_wokanda,"sesja_koniec"), 0,5);
		if (($godzina_koniec_rozp==null) || ($godzina_koniec_rozp=='03:00') || ($godzina_koniec_rozp=='00:00:') || ($godzina_koniec_rozp=='01:00:')) {$godzina_koniec_rozp=''; }
		if (($sesja_pocz==null) || ($sesja_pocz=='03:00') || ($sesja_pocz=='00:00:') || ($sesja_pocz=='01:00:')) {$sesja_pocz=''; }

      echo 'Sesję rozpoczęto o godz: ' . $sesja_pocz;
      echo '<br> Sesję zakończono o godz: ' . $godzina_koniec_rozp;
		} else {
			echo 'Brak sesji dla tej sali na dziś';
		}
?>

</td>
<td class="header-court-people" style="visibility: visible">
<?php
if ($ile_wierszy <> 0)
	 {
 echo 'Wydział: ' . iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"wydzial")) . '<br>';
 echo 'Przewodniczący: '. iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"przewodniczacy")) . '<br>';
 echo 'Protokolant: '. iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"protokolant"));
	 }
?>
</td></tr>
</tbody>
</table>
</span></div>
</td></tr><tr>
<td height="1037" style="vertical-align: top;"><span id="mainForm:beforeRendered:temida"><div id="mainForm:beforeRendered:j_id154" style="text-align: center;">

<?php
function temida()
{ ?>
<img src="<?php echo $GLOBALS['temida_image']; ?>" height='1075'>
<?php
}
?>

<?php
  $ile_wierszy = odbc_num_rows($result_wokanda); // num_rows - 1 = ilość pobranych wierszy
 // echo $ile_wierszy;

     if ($ile_wierszy == 0)
	 { temida(); // Brak rozpraw - wyświetl temidę.
	 } else  {
      gennaglowek();
	 }
$licznik=1;
function gennaglowek(){
	?>
<div class="container-fluid">
	<div class="row">
		<div class="table-responsive">
<table class="table table-striped table-hover table-bordered" cellspacing="0">
<thead class="thead-inverse">
<th style="text-align: center">L.p.</th>
<th style="text-align: center">Sygn. akt</th>
<th style="text-align: center">Godz.</th>
<th style="text-align: center">Imiona i nazwiska stron</th>
<th style="text-align: center">Uwagi</th>
</thead>
<tbody id="wokanda">
<?php
}

$zapytanie_wokanda= 'Select * from wokanda where numer_sali=' . '\'' . $sala_rozp . '\'' . ' ORDER BY godzina_start ASC';
$result_wokanda = odbc_exec($conn_wokanda, $zapytanie_wokanda);

// Generowane zawartości tabeli wokandy
while(odbc_fetch_row($result_wokanda)){
     if ($ile_wierszy == 0)
	 { temida(); // Brak rozpraw - wyświetl temidę.
      } else {  // Są rozprawy - wyświetl rozprawy.
	  echo '<tr><td width="5%" style=\"text-align: center><h4>' . $licznik .'</h4></td>';
	 $nowy = iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,1));

	 
	 // Kolumna Sygnatura akt
	 $prokur='';
	 if(trim(odbc_result($result_wokanda,"wydz"))=='kar') { 
	 echo '<td width="23%" style=\"text-align: center><h2>' . odbc_result($result_wokanda,"sygnatura") . '</h2>' .
	  '<i>' . iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"oskarzyciel")) . '</i><br><b>' . odbc_result($result_wokanda,"rep_oskarzyciela") . '</b>';
  	 $prok222=iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"prokurator"));
	 if(strlen($prok222)>1) echo '<br><br>Prokurator: ' . iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"prokurator")) . '<br>'; 
	}
	 if(trim(odbc_result($result_wokanda,"wydz"))=='rodz') { 
	 echo '<td width="23%" style=\"text-align: center><h2>' . odbc_result($result_wokanda,"sygnatura") . '</h2>' ;
  	 $prok222=iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"prokurator"));
	 if(strlen($prok222)>1) echo '<br><br>Prokurator: ' . iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"prokurator")) . '<br>'; 
	}

	 if(trim(odbc_result($result_wokanda,"wydz"))=='cyw') { 
	 echo '<td width="23%" style=\"text-align: center><h2>' . odbc_result($result_wokanda,"sygnatura") . '</h2>';
  	 $prok222=iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"prokurator"));
	 if(strlen($prok222)>1) echo '<br><br>Prokurator: ' . iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"prokurator")) . '<br>'; 
	}
	 echo '<b><br>' . odbc_result($result_wokanda,"tryb") . '</b>';
	 echo '</td>';

	  // Kolumna Godzina
		if ((strlen(odbc_result($result_wokanda,"godzina_stop")) > 0) && (odbc_result($result_wokanda,"godzina_stop")!='00:00:00.0000000') && (odbc_result($result_wokanda,"godzina_stop")!='03:00:00.0000000')  ) {
		$godz_stop2=date_create(odbc_result($result_wokanda,"godzina_stop"));
		$godz_stop2=date_format($godz_stop2, 'H:i');
	}
		else $godz_stop2=null;

  $data_rozprawy_pozycja = date_create(odbc_result($result_wokanda,"godzina_start"));
	 echo '<td style=\"text-align: center><h3><center>';
		echo date_format($data_rozprawy_pozycja, 'H:i');
		echo '</center>';
    echo '<br>';
    echo '<h5>' . $godz_stop2 . '</h5>';
 	  echo '</h3></td>';

// Kolumna osoby
	$wok_oskarzyciel = iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"oskarzyciel"));
	$wok_oskar = iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"oskarzeni"));
	$wok_obroncy= iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"obroncy"));
	$wok_str_inne= iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"strony_inne"));
	$wok_strony= iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"strony"));
	$wok_kwalifikacja= iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"kwalifikacja"));
	$wok_przedmiot= iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"przedmiot"));
	echo '<td style=\"text-align: center>';

	if(trim(odbc_result($result_wokanda,"wydz"))=='kar') {  // DLA KARNEGO
	$defaultrowperpagespge = $defaultrowperpagespge_kar;
	if (strlen($wok_oskar)>0) { echo '<br><h4><b>' . $wok_oskar . '</b></h4>'; }
	if (strlen($wok_obroncy)>0) { echo '<br><h4>'. $wok_obroncy . '</b></h4>'; }
	if (strlen($wok_str_inne)>0) { echo '<h4><br>'.$wok_str_inne . '</h4>'; }	
 if($karny_kwalifikacja==true) {	if (strlen($wok_kwalifikacja)>1) { echo '<br>' . $wok_kwalifikacja; }	 }
if($karny_przedmiot==true) { if (strlen($wok_przedmiot)>1) { echo '<i><br>' . $wok_przedmiot . '</i>'; }	}} // Koniec dla karnego 
 
 if(trim(odbc_result($result_wokanda,"wydz"))=='rodz') {  // DLA RODZINNEGO
    $defaultrowperpagespge = $defaultrowperpagespge_rodz;
    if (strlen($wok_oskarzyciel)>0) { echo '<h4><b>' . $wok_oskarzyciel . '</b></h4>'; }
	if (strlen($wok_oskar)>0) { echo '<h4><b>' . $wok_oskar . '</b></h4>'; }
	if (strlen($wok_obroncy)>0) { echo '<h4>'. $wok_obroncy . '</b></h4>'; }
	if (strlen($wok_str_inne)>0) { echo '<h4>'.$wok_str_inne . '</h4>'; }	
 if($rodzinny_kwalifikacja==true) {	if (strlen($wok_kwalifikacja)>1) { echo '<br>' . $wok_kwalifikacja; }	 }
 if($rodzinny_przedmiot==true) { if (strlen($wok_przedmiot)>1) { echo '<i><br></b>' . $wok_przedmiot . '</i>'; }	} } //Koniec dla rodzinnego

if(trim(odbc_result($result_wokanda,"wydz"))=='cyw') {  // DLA CYWILNEGO
    $defaultrowperpagespge = $defaultrowperpagespge_cyw;
    if (strlen($wok_oskarzyciel)>0) { echo '<h4><b>' . $wok_oskarzyciel . '</b></h4>'; }
	if($cywilny_przedmiot==true) { if (strlen($wok_przedmiot)>1) { echo '<i>' . $wok_przedmiot . '</i>'; }	} 
	if (strlen($wok_oskar)>1) { echo '<h4>' . $wok_oskar . '</h4>'; }
	if (strlen($wok_strony)>1) { echo '<h4>' . $wok_strony . '</h4>'; }
	if (strlen($wok_obroncy)>1) { echo '<h5>'. $wok_obroncy . '</h5>'; }
	if (strlen($wok_str_inne)>1) { echo '<h4>' . $wok_str_inne . '</h4>'; }
	if($cywilny_kwalifikacja==true) {	if (strlen($wok_kwalifikacja)>1) { echo $wok_kwalifikacja; } } }
	echo '</td>';

// Kolumna UWAGI
	 echo '<td width="20%" style=\"text-align: center>';
	 echo ' <h4>' . iconv("Windows-1250", "UTF-8", odbc_result($result_wokanda,"wynik")). '</h4>';
   echo '</td>';
$licznik++;
} }
?>
</tbody>
</table>
</div> <!-- Table responsive -->
</div> <!-- ROW -->
</div> <!-- Containder -->
<div class="col-md-12 text-center">
		<ul class="pagination pagination-lg pager" id="paginatorek"></ul>
		</div>
</div>
</span></td></tr>
<script type="text/javascript">
$.fn.pageMe = function(opts){
    var $this = this,
        defaults = {
            perPage: 20,
            showPrevNext: false,
            hidePageNumbers: false
        },
        settings = $.extend(defaults, opts);

    var listElement = $this;
    var perPage = settings.perPage;
    var children = listElement.children();
    var pager = $('.pager');
	var pagtime = <?php echo json_encode($defaulttimeperpage); ?>;

    if (typeof settings.childSelector!="undefined") {
        children = listElement.find(settings.childSelector);
    }

    if (typeof settings.pagerSelector!="undefined") {
        pager = $(settings.pagerSelector);
    }

    var numItems = children.size();
    var numPages = Math.ceil(numItems/perPage);

    pager.data("curr",0);

    if (settings.showPrevNext){
        $('<li><a href="#" class="prev_link">«</a></li>').appendTo(pager);
    }

    var curr = 0;
    while(numPages > curr && (settings.hidePageNumbers==false)){
        $('<li><a href="#" class="page_link">'+(curr+1)+'</a></li>').appendTo(pager);
        curr++;
    }

    if (settings.showPrevNext){
        $('<li><a href="#" class="next_link">»</a></li>').appendTo(pager);
    }

    pager.find('.page_link:first').addClass('active');
    pager.find('.prev_link').hide();
    if (numPages<=1) {
        pager.find('.next_link').hide();
    }
  	pager.children().eq(1).addClass("active");

    children.hide();
    children.slice(0, perPage).show();

    pager.find('li .page_link').click(function(){
        var clickedPage = $(this).html().valueOf()-1;
        goTo(clickedPage,perPage);
        return false;
    });
    pager.find('li .prev_link').click(function(){
        previous();
        return false;
    });
    pager.find('li .next_link').click(function(){
        next();
        return false;
    });

    function previous(){
        var goToPage = parseInt(pager.data("curr")) - 1;
        goTo(goToPage);
    }

    function next(){
        goToPage = parseInt(pager.data("curr")) + 1;
        goTo(goToPage);
    }

    function goTo(page){
        var startAt = page * perPage,
            endOn = startAt + perPage;

        children.css('display','none').slice(startAt, endOn).show();
        pager.data("curr",page);
      	pager.children().removeClass("active");
        pager.children().eq(page+1).addClass("active");
    }
	 pager.find('.prev_link').hide();
   pager.find('.next_link').hide();

if(numPages>1) {
		        ctr=1;
		        setInterval(function(){
				if (ctr < numPages){
	      	next();
				 ctr=ctr+1;
				 } else {
				  goTo(0);
				  ctr=1;
				} },pagtime); // Czas na zmianę paginacji - 8 sekund
		setInterval(function(){
		    //Oczekiwanie
		}, pagtime);
	}
};

$(document).ready(function(){
	var spge = <?php echo json_encode($defaultrowperpagespge); ?>;	
	$('#wokanda').pageMe({pagerSelector:'#paginatorek',showPrevNext:true,hidePageNumbers:false,perPage:spge});
});
</script>
</body>
</html>
