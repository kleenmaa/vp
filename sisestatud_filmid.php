<?php
	require_once "../../config.php";

	$tile = null;
	$year = null;
	$duration = null;
	$genre = null;
	$studio = null;
	$director = null;
	
	$title_error = null;
	$year_error = null;
	$duration_error = null;
	$genre_error = null;
	$studio_error = null;
	$director_error = null;
	//tegeleme päevale antud hinde ja kommentaariga
	
	if(isset($_POST["film_submit"])){
		if(isset($_POST["title_input"]) and !empty($_POST["title_input"])) {
			$title = $_POST["title_input"];
		} else {
			$title_error = "Filmi pealkiri jäi lisamata!";
		}

		if(isset($_POST["year_input"]) and !empty($_POST["year_input"])) {
			$year = $_POST["year_input"];
		} else {
			$year_error = "Filmi valmimisaasta jäi lisamata!";
		}

		if(isset($_POST["duration_input"]) and !empty($_POST["duration_input"])) {
			$duration = $_POST["duration_input"];
		} else {
			$duration_error = "Filmi kestus jäi lisamata!";
		}

		if(isset($_POST["genre_input"]) and !empty($_POST["genre_input"])) {
			$genre = $_POST["genre_input"];
		} else {
			$genre_error = "Filmi žanr jäi lisamata!";
		}

		if(isset($_POST["studio_input"]) and !empty($_POST["studio_input"])) {
			$studio = $_POST["studio_input"];
		} else {
			$studio_error = "Filmi stuudio jäi lisamata!";
		}

		if(isset($_POST["director_input"]) and !empty($_POST["director_input"])) {
			$director = $_POST["director_input"];
		} else {
			$director_error = "Filmi režissöör jäi lisamata!";
		}

		if(empty($title_error and $year_error and $duration_error and $genre_error and $studio_error and $director_error)){
			//loome andmebaasiühenduse
			$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
			//määrame suhtlemisel kasutatava kooditabeli
			$conn->set_charset("utf8");
			//valmistame ette SQL keeles päringu
			$stmt = $conn->prepare("INSERT INTO vp_film (title, year, duration, genre, studio, director) VALUES(?,?,?,?,?,?)");
			echo $conn->error;
			//seome SQL päringu päris andmetega
			//määrata andmetüübid i - integer(täisarv) d - decimal(murdarv) s - string(tekst)
			$stmt->bind_param("siisss", $title, $year, $duration, $genre, $studio, $director);
			$stmt->execute();
			echo $stmt->error;
			//aitab sellest käsust $stmt->close();
			$stmt->close();
			//sulgeme andmebaasiühenduse
			$conn->close();
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<a href="https://greeny.cs.tlu.ee/~rinde/vp_2022/vp_banner_gs.png"><img src="pildid/vp_banner_gs.png" alt="Veebiprogrammeerimine banner"></a>
	<title>Katriin Liselle Eenmaa, veebiprogrammeerimine</title>
</head>
<body>
<form method="POST">
	<label for="title_input">Filmi pealkiri</label>
  <input type="text" name="title_input" id="title_input" placeholder="filmi pealkiri" value="<?php echo $title; ?>">
	<span><?php echo $title_error; ?></span>
  <br>
  <label for="year_input">Valmimisaasta</label>
  <input type="number" name="year_input" id="year_input" min="1912" value="<?php echo $year; ?>">
	<span><?php echo $year_error; ?></span>
  <br>
  <label for="duration_input">Kestus</label>
  <input type="number" name="duration_input" id="duration_input" min="1" value="60" max="600" value="<?php echo $duration; ?>">
	<span><?php echo $duration_error; ?></span>
  <br>
  <label for="genre_input">Filmi žanr</label>
  <input type="text" name="genre_input" id="genre_input" placeholder="žanr" value="<?php echo $genre; ?>">
	<span><?php echo $genre_error; ?></span>
	<br>
  <label for="studio_input">Filmi tootja</label>
  <input type="text" name="studio_input" id="studio_input" placeholder="filmi tootja" value="<?php echo $studio; ?>">
	<span><?php echo $studio_error; ?></span>
	<br>
  <label for="director_input">Filmi režissöör</label>
  <input type="text" name="director_input" id="director_input" placeholder="filmi režissöör" value="<?php echo $director; ?>">
	<span><?php echo $director_error; ?></span>
	<br>
  <input type="submit" name="film_submit" value="Salvesta">
</form>
</body>
</html<