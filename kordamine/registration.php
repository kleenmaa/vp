<?php
require_once "../../../config.php";
require_once "fnc_registration.php";


	$notice = null;
	$first_name = null;
    $last_name = null;
    $uliopilaskood = null;
	$registered_count = null;
	$paid_count = null;


    //muutujad võimalike veateadetega
    $first_name_error = null;
    $last_name_error = null;
    $uliopilaskood_error = null;

	//kontrollime sisestust
	if($_SERVER["REQUEST_METHOD"] === "POST"){
		if(isset($_POST["user_data_submit"])){

			if(isset($_POST["first_name_input"]) and !empty($_POST["first_name_input"])){
				$first_name = ($_POST["first_name_input"]);
				if($first_name != $_POST["first_name_input"]){
					$first_name_error = "Palun kontrolli oma eesnime, et seal poleks keelatud märke!";
				}
			} else {
				$first_name_error = "Palun sisesta eesnimi!";
			}

			if(isset($_POST["last_name_input"]) and !empty($_POST["last_name_input"])){
				$last_name = ($_POST["last_name_input"]);
				if($last_name != $_POST["last_name_input"]){
					$last_name_error = "Palun kontrolli oma perekonnanime, et seal poleks keelatud märke!";
				}
			} else {
				$last_name_error = "Palun sisesta perekonnanimi!";
			}

			if(isset($_POST["uliopilaskood_input"]) and !empty($_POST["uliopilaskood_input"])){
				$uliopilaskood = ($_POST["uliopilaskood_input"]);
				if($uliopilaskood != $_POST["uliopilaskood_input"]){
					$uliopilaskood_error = "Palun kontrolli sisestatud üliõpilaskoodi!";
				}
			} else {
				$uliopilaskood_error = "Palun sisesta üliõpilaskood!";
			}


            //kui kõik kombes, salvestame uue kasutaja
            if(empty($firstname_error) and empty($last_name_error) and empty($uliopilaskood_error)){
				//salvestame andmetabelisse
				$notice = registration($first_name, $last_name, $uliopilaskood);
				if($notice == 1){
					$notice = "Oled edukalt peole registreeritud!";
					//$notice = null;
					$first_name = null;
					$last_name = null;
					$uliopilaskood = null;
				} else {
					if($notice == 2){
						$notice = "Oled juba peole registreerunud!";
					} else {
						$notice = "Peole registreerimisega tekkis tõrge!";
					}
				}
			}
		}//if submit lõppeb
	}//if POST lõppeb
	
	$registered_count = count_registered();
	$paid_count = count_paid();
?>

<!DOCTYPE html>
<html lang="et">
  <head>
    <meta charset="utf-8">

  </head>
  <body>

	<hr>
    <h2>Registreerimine peole</h2>

	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="first_name_input">Eesnimi:</label><br>
	  <input name="first_name_input" id="first_name_input" type="text" value="<?php echo $first_name; ?>"><span><?php echo $first_name_error; ?></span><br>
      <label for="lastname_input">Perekonnanimi:</label><br>
	  <input name="last_name_input" id="last_name_input" type="text" value="<?php echo $last_name; ?>"><span><?php echo $last_name_error; ?></span>
	  <br>
	  <label for="uliopilaskood_input">Üliõpilaskood:</label><br>
	  <input name="uliopilaskood_input" id="uliopilaskood_input" type="text" value="<?php echo $uliopilaskood; ?>"><span><?php echo $uliopilaskood_error; ?></span><br>
	  <input name="user_data_submit" type="submit" value="Registreeru">
	  <span><?php echo $notice; ?></span>
	</form>
	<p>Peole on registreerunud: <span><?php echo $registered_count; ?></span> inimest.</p>
	<p>Peo eest on maksnud: <span><?php echo $paid_count; ?></span> inimest.</p>