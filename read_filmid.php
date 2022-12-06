<?php
	//session_start();
	require_once "classes/SessionManager.class.php";
	SessionManager::sessionStart("vp", 0, "~eenmkatr/vp/", "greeny.cs.tlu.ee");
	if(!isset($_SESSION["user_id"])){
		//jõuga viiakse page.php lehele
		header("Location: page.php");
		exit();
	}

	//logime välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
		exit();
	}

	require_once "../../config.php";

	//loome andmebaasiühenduse
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	//määrame suhtlemisel kasutatava kooditabeli
	$conn->set_charset("utf8");
	//valmistame ette SQL keeles päringu
	$stmt = $conn->prepare("SELECT title, year, duration, genre, studio, director FROM vp_film");
	echo $conn->error;
	//seome loetavad andmed muutujatega
	$stmt->bind_result($title_from_db, $year_from_db, $duration_from_db, $genre_from_db, $studio_from_db, $director_from_db);
	//täidame käsu
	$stmt->execute();
	echo $stmt->error;
	//võtan andmeid
	//kui on oodata vaid üks võimalik kirje
	//if($stmt->fetch) {
		//kõik mida teha
	// }

	$filmid_html = null;

	//kui on oodata mitut aga teadmata arv
	while($stmt->fetch()) {
		//  <p>Kommentaar, hinne päevale: x, lisatud yyyyyy.</p>
		$filmid_html .= "<h3>" .$title_from_db ."</h3>"
		."<ul>"
		."<li>Valmimisaasta: " .$year_from_db ."</li>"
		."<li>Kestus minutites: " .$duration_from_db ."</li>"
		."<li>Žanr: " .$genre_from_db ."</li>"
		."<li>Tootja: " .$studio_from_db ."</li>"
		."<li>Lavastaja: " .$director_from_db ."</li>"
		."</ul>";
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
	<h1>Katriin Liselle Eenmaa, filmide loetelu</h1>
	<?php echo $filmid_html ?>
	<ul>
		<li>Logi <a href="?logout=1">välja</a></li>
	</ul>
</body>
</html<
