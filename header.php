<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Katriin Liselle Eenmaa, veebiprogrammeerimine</title>
	<style>
		body {
		background-color: <?php echo $_SESSION["user_bg_color"]; ?>;
		color: <?php echo $_SESSION["user_txt_color"]; ?>

		}
	</style>
	<?php
		if(isset($style_sheets) and !empty($style_sheets)) {
			foreach($style_sheets as $style) {
			//<link rel="stylesheet" href="styles/gallery.css">
				echo '<link rel="stylesheet" href="' .$style . '">' ."\n";
			}
		}	
		
		if(isset($javascripts) and !empty($javascripts)) {
			foreach($javascripts as $js) {
			//<script src="javascript.js" defer></script>
				echo '<script src="' .$js .'" defer></script>' ."\n";
			}
		}

//require_once "user_profile.php";
	?>
</head>
<body>
<a href="https://greeny.cs.tlu.ee/~rinde/vp_2022/vp_banner_gs.png"><img src="pildid/vp_banner_gs.png" alt="Veebiprogrammeerimine banner"></a>
<h1>Katriin Liselle Eenmaa vinge veebisüsteem</h1>
<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
<p>Õppetöö toimus <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikoolis</a> Digitehnoloogiate instituudis.</p>
