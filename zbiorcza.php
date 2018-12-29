<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php include ('config.php'); ?>
<meta http-equiv="refresh" content="60">
<title>Wokanda Sądu Rejonowego</title>
<script type="text/javascript" src="js/date_time.js"></script>
</head>
<body onload=display_ct();>
<?php
 include('config.php');
 $licznik = 0;
 $zapytanie_wokanda="SELECT * FROM wokanda WHERE aktywny=1 ORDER BY godzina_start ASC";
 $result_wokanda = odbc_exec($conn_wokanda, $zapytanie_wokanda);
 ?>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr>
<td height="205px" style="vertical-align: top;"><div class="header header-sala" id="naglowek2">
<table class="header-top header-top-sala" border="0">
<tbody><tr>
<td style="vertical-align: bottom;">
<div class="court-info-sala"> <?php echo $jaki_sad ?> </div>
<span class="header-pair-sala header-pair-value">
<span id="mainForm:beforeRendered:day">
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
			} else {
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
<div class="hour hour-sala" style="line-height: 1;"><span id="mainForm:beforeRendered:hour">
  <span id="date_time"></span>
  <script type="text/javascript">window.onload = date_time('date_time'); </script>
</div></td></tr></tbody></table>
<div class="header-ruler"></div>
<span id="mainForm:beforeRendered:room-people">
<center><h2>LISTA DZISIEJSZYCH SPRAW W SĄDZIE</h2></center>
<table class="header-bottom header-bottom-sala">
<tbody><tr><td style="vertical-align: bottom;">
</td></tr></tbody></table></span></div></td></tr><tr>
<td height="1037" style="vertical-align: top;">
<span id="mainForm:beforeRendered:temida">
<div id="mainForm:beforeRendered:j_id154" style="text-align: center;">

<?php
function temida()
{ ?>
<img src="<?php echo $GLOBALS['temida_image']; ?>" height='1075'>
<?php
}

$ile_wierszy = odbc_num_rows($result_wokanda); // num_rows - 1 = ilość pobranych wierszy

  if ($ile_wierszy == 0)
	 { temida(); // Brak rozpraw - wyświetl temidę.
	 } else  {
     gennaglowek();
	 }

function gennaglowek(){
?>
<div class="container-fluid">
<div class="row">
<div class="table-responsive">
<table class="table table-striped table-hover table-bordered" cellspacing="0">
<thead class="thead-inverse">
<th style="text-align: center">L.p.</th>
<th style="text-align: center">Sygnatura akt</th>
<th style="text-align: center">Godzina rozpoczęcia</th>
<th style="text-align: center">Sala rozpraw</th>
</thead>
<tbody id="wokanda">
<?php
}
  if ($ile_wierszy == 0)
	 { temida(); // Brak rozpraw - wyświetl temidę.
      } else {  // Są rozprawy - wyświetl rozprawy.
  $lp = 1;
while(odbc_fetch_row($result_wokanda)){
    echo '<tr>';
	  echo '<td><center><h3>' . $lp .'</center></td></h3>';

	// Kolumna Sygnatura akt
	 echo '<td style=\"text-align: center><h2>' . Trim(addslashes(odbc_result($result_wokanda,"sygnatura"))) .'</h2><br></b>';
	 echo '</td>';

	  // Kolumna Godzina
	 $data_rozprawy_pozycja = date_create(odbc_result($result_wokanda,"godzina_start"));
 	 echo '<td><h2><center>' . date_format($data_rozprawy_pozycja,'H:i') . '</center></h2>';
	 echo '</td>';

	 // Kolumna Sala rozpraw
    echo '<td style=\"text-align: center>';
		echo '<h2>' . Trim(odbc_result($result_wokanda,"numer_sali")) . '</h2>';
    echo '</td>';
     echo '</tr>';
 	  $lp++;
}
}
?>
	</div></div></span></td></tr></tbody></table>
  </div></div></div>
  <div class="col-md-12 text-center"><ul class="pagination pagination-lg pager" id="paginatorek"></ul>
   
</body>
</html>
