<?php
	require_once "../config.php";
	
	//loome andmebaasiühenduse
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	//määrame suhtlemisel kasutatava kooditabeli
	$conn->set_charset("utf8");
	//valmistame ette SQL keeles päringu
	$stmt = $conn->prepare("SELECT comment, grade, added FROM vp_daycomment");
	echo $conn->error;
	//seome loetavad andmed muutujatega
	$stmt->bind_result($comment_form_db, $grade_from_db, $added_from_db);
	//täidame käsu
	$stmt->execute();
	echo $stmt->error;
	//võtan andmeid
	//kui on oodata vaid üks võimalik kirje
	//if($stmt->fetch) {
		//kõik mida teha
	// }
	
	$comments_html = null; 
	//kui on oodata mitut aga teadmata arv
	while($stmt->fetch()) {
		//  <p>Kommentaar, hinne päevale: x, lisatud yyyyyy.</p>
		$comments_html .= "<p>" .$comment_form_db ." Hinne päevale: " .$grade_from_db .", lisatud " .$added_from_db .".</p> \n";
	}
	//aitab sellest käsust $stmt->close();
	$stmt->close();
	//sulgeme andmebaasiühenduse
	$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<a href="https://greeny.cs.tlu.ee/~rinde/vp_2022/vp_banner_gs.png"><img src="pildid/vp_banner_gs.png" alt="Veebiprogrammeerimine banner"></a>
	<title>Katriin Liselle Eenmaa, veebiprogrammeerimine</title>
</head>
<body>
	<h1>Katriin Liselle Eenmaa, veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist infot.</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee">Tallinna Ülikoolis</a>, Digitehnoloogiate instituudis.</p>
	<a href="https://www.tlu.ee"><img src="pildid/tlu_39.jpg" alt="Tallinna Ülikooli õppehoone"></a>
	<p>Minu nimi on Katriin, ma olen 21-aastane ja õpin Tallinna Ülikoolis informaatikat, digitaalse meedia suunal.</p>
	<?php echo $comments_html ?>
</body>
</html<