<?php
require_once "../../../config.php";
require_once "fnc_registration.php";


	$notice = null;
	
    $uliopilaskood = null;
	
	


    //muutujad võimalike veateadetega

    $uliopilaskood_error = null;

	//kontrollime sisestust
	if($_SERVER["REQUEST_METHOD"] === "POST"){
		if(isset($_POST["user_data_submit"])){

			if(isset($_POST["uliopilaskood_input"]) and !empty($_POST["uliopilaskood_input"])){
				$uliopilaskood = ($_POST["uliopilaskood_input"]);
				if($uliopilaskood != $_POST["uliopilaskood_input"]){
					$uliopilaskood_error = "Palun kontrolli sisestatud üliõpilaskoodi!";
				}
			} else {
				$uliopilaskood_error = "Palun sisesta üliõpilaskood!";
			}


            //kui kõik kombes, salvestame uue kasutaja
            if(empty($uliopilaskood_error)){
				//salvestame andmetabelisse
				$notice = cancel_registration($uliopilaskood);
				if($notice == 1){
					$notice = "Oled edukalt tühistanud peole registreerimise!";
					//$notice = null;
					$uliopilaskood = null;
				} else {
					if($notice == 2){
						$notice = "Tühistamine ebaõnnestus!";
	
				}
			}
		}//if submit lõppeb
	}//if POST lõppeb
	}
?>

<!DOCTYPE html>
<html lang="et">
  <head>
    <meta charset="utf-8">

  </head>
  <body>
    <h2>Tühista peole registreerumine</h2>

	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="uliopilaskood_input">Üliõpilaskood:</label><br>
	  <input name="uliopilaskood_input" id="uliopilaskood_input" type="text" value="<?php echo $uliopilaskood; ?>"><span><?php echo $uliopilaskood_error; ?></span><br>
	  <br>
	  <input name="user_data_submit" type="submit" value="Tühista">
	  <span><?php echo $notice; ?></span>
	</form>