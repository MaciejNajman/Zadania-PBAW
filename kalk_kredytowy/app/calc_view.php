<?php require_once dirname(__FILE__) .'/../config.php';?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
<meta charset="utf-8" />
<title>Kalkulator kredytowy</title>
</head>
<body>

<form action="<?php print(_APP_URL);?>/app/calc.php" method="post">
	<label for="id_kwota">Kwota: </label>
	<input id="id_kwota" type="text" name="kwota" value="<?php print($kwota); ?>" /><br />
	<label for="id_ile_lat">Czas splaty w latach: </label>
	<input id="id_ile_lat" type="text" name="ile_lat" value="<?php print($ile_lat); ?>" /><br />
	<label for="id_opr">Oprocentowanie: </label>
	<input id="id_opr" type="text" name="opr" value="<?php print($oprocentowanie); ?>" /><br />
	<input type="submit" value="Oblicz" />
</form>	

<?php
//wyœwieltenie listy b³êdów, jeœli istniej¹
if (isset($messages)) {
	if (count ( $messages ) > 0) {
		echo '<ol style="margin: 20px; padding: 10px 10px 10px 30px; border-radius: 5px; background-color: #f88; width:300px;">';
		foreach ( $messages as $key => $msg ) {
			echo '<li>'.$msg.'</li>';
		}
		echo '</ol>';
	}
}
?>

<?php if (isset($result)){ ?>
<div style="margin: 20px; padding: 10px; border-radius: 5px; background-color: #ff0; width:300px;">
<?php echo 'Miesieczna rata: '.$result; ?>
</div>
<?php } ?>

</body>
</html>