<?php
	//algatan sessiooni
	session_start();
	//loen sisse konfiguratsioonifaili
	require_once "fnc_user.php"
	// loen sisse konfiguratsioonifaili
	//require_once "../../config.php";
	//echo $server_host;
	$author_name = "Katriin Liselle Eenmaa";
	//echo $author_name;
	$full_time_now = date("d.m.Y H:i:s");
	$weekday_names_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	//echo $weekday_names_et[2];
	$weekday_now = date("N");
	$hour_now = date("H");
	$part_of_day = "suvaline hetk";
	//   == on võrdne  != pole võrdne  <  >  <=  >=
	// and  or
	$photo_number = 1;


	if($weekday_now <=5) {
		if($hour_now < 7) {
			$part_of_day = "uneaeg";
		}
		if($hour_now > 7 and $hour_now < 8) {
			$part_of_day = "aeg kooli sättida";
		}
		if($hour_now >= 8 and $hour_now < 18) {
			$part_of_day = "koolipäev";
		}
		if($hour_now > 18 and $hour_now < 20) {
			$part_of_day = "kodutööde tegemise aeg";
		}
		if($hour_now > 20 and $hour_now < 22) {
			$part_of_day = "vaba aeg";
		}
		if($hour_now > 22 and $hour_now < 23) {
			$part_of_day = "aeg magama sättida";
		}
	}

	if($weekday_now == 6) {
		if($hour_now < 10) {
			$part_of_day = "uneaeg";
		}
		if($hour_now > 10 and $hour_now < 19) {
			$part_of_day = "logelemise aeg";
		}
		if($hour_now > 19 and $hour_now < 23) {
			$part_of_day = "filmi vaatamise aeg";
		}
	}

	//if($weekday_now == 7) {


	//vaatame semestri pikkust ja kulgemist
	$semester_begin = new DateTime("2022-09-05");
	$semester_end = new DateTime("2022-12-18");
	$semester_duration = $semester_begin->diff($semester_end);
	$semester_duration_days = $semester_duration->format("%r%a");
	// echo $semester_duration_days;
	$from_semester_begin = $semester_begin->diff(new DateTime("now"));
	$from_semester_begin_days = $from_semester_begin->format("%r%a");
	// echo $from_semester_begin_days

	//loendan massiivi (array) liikmeid
	//echo count($weekday_names_et);
	//juhuslik arv
	//echo mt_rand(1,9);
	//juhuslik element massiivist
	$random_quote_et = ["Enne töö, siis lõbu.", "Suure naeru järel tuleb ikka nutt.", "Tahad sõbrast lahti saada, laena talle raha.", "Tark mõtleb esiti, rumal kahetseb pärast.", "Tarkus on enam kui rikkus.", "Ütle, kes on su sõbrad ja ma ütlen, kes oled sa ise."];
	//echo $random_quote_et[mt_rand(0, count($random_quote_et) - 1)];
	//echo $weekday_names_et[mt_rand(0, count($weekday_names_et) - 1)];

	//loeme fotode kataloogi sisu
	$photo_dir = "photos/";
	//$all_files = scandir($photo_dir);
	//uus_massiiv = array_slice(massiiv,mis kohast alates);
	$all_files = array_slice(scandir($photo_dir),2);
	//var_dump($all_files);

	$photo_html = null;

	//tsükkel
	//muutuja väärtuse suurendamine: $muutuja = $muutuja +
	//muutuja += 5
	// kui suureneb 1 võrra siis $muutuja ++
	/*for($i = 0; $i < count($all_files); $i ++){
		echo $all_files[$i];
	}*/
	/*foreach($all_files as $file_name){
		echo $file_name . " | ";
	}*/

	//kasutajaga sisse logimine ja selle õiguste kontrollimine
	//$email = null;
	$password = null;


	//muutujad võimalike veateadetega
	$email_error = null;
	$password_error = null;
	$login_error = null;

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST["user_data_submit"])) {

			$email = $_POST["email_input"];
			$password = $_POST["password_input"];


            if(empty($email_error)){
				$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
				$conn->set_charset("utf8");
				$stmt = $conn->prepare("SELECT password FROM vp_users WHERE email = ?");
				echo $conn->error;

				//määrata andmetüübid i - integer(täisarv) d - decimal(murdarv) s - string(tekst)
				$stmt->bind_param("s", $email);
				$stmt->bind_result($password_from_db);
				$stmt->execute();
				if($stmt->fetch()) {
					if(password_verify($password, $password_from_db)) {
						$stmt->close();
						$conn->close();
						header("Location: home.php");
						exit();
					} else {
						$login_error = "Sisselogimine ebaõnnestus, salasõna või meiliaadress oli ebakorrektne!";
					}
				} else {
					$login_error = "Sisselogimine ebaõnnestus, salasõna või meiliaadress oli ebakorrektne!";
				}

				$stmt->close();
				$conn->close();
			}
		}
	}
	//loetlen lubatud failitüübid (jpg png)
	$allowed_photo_types = ["image/jpeg", "image/png"];
	$photo_files = [];
	foreach($all_files as $file_name){
		$file_info =  getimagesize($photo_dir .$file_name);
		if(isset($file_info["mime"])){
			if(in_array($file_info["mime"], $allowed_photo_types)){
				array_push($photo_files, $file_name);
			}
		}
	}

	//<hr> tühi rida

	$photo_html = '<img src="' .$photo_dir .$photo_files[mt_rand(0, count($photo_files) - 1)] .'" alt="Tallina pilt">';
	//echo $photo_html;
	//vorm info kasutamine
	// $_POST
	//var_dump($_POST);
	$adjective_html = null;
	if(isset($_POST["todays_adjective_input"]) and !empty($_POST["todays_adjective_input"])){
		$adjective_html = "<p>Tänase kohta on arvatud: " .$_POST["todays_adjective_input"] ."</p>";
	}
	//var_dump($photo_files);
	//käsitisi optionite lisamine <option value="0">tln_004.JPG</option>

	//kui kasutaja on ise foto valinud, määran valiku järgi foto numbrit
	if(isset($_POST["photo_select"]) and $_POST["photo_select"] >= 0){
		//kõik, mis teha tahame ...
		$photo_number = $_POST["photo_select"];

	}
	//teen fotode rippmenüü ja arvestan seejuures numbriga, millist fotot näidatakse (see paistab rippmenüüs valituna)
	//   <option value="0" selected>tln_1.JPG</option>
	$select_html = '<option value="" selected disabled>Vali pilt</option>';
	for($i = 0; $i < count($photo_files); $i ++){
		$select_html .= '<option value="' .$i .'"';
		if($i == $photo_number){
			$select_html .= " selected";
		}
		$select_html .= ">";
		$select_html .= $photo_files[$i];
		$select_html .= "</option> \n";
	}


	//kasutades loositud või kasutaja valitud numbrit, määran näidatava foto
	$photo_html = '<img src="' .$photo_dir .$photo_files[$photo_number] .'" alt="Tallinna pilt">';

	$comment_error = null;
	//tegeleme päevale antud hinde ja kommentaariga
	if(isset($_POST["comment_submit"])) {
		if(isset($_POST["comment_input"]) and !empty($_POST["comment_input"])) {
			$comment = $_POST["comment_input"];
		} else {
			$comment_error =  "Kommentaar jäi lisamata!";
		}
		$grade = $_POST["grade_input"];

		if(empty($comment_error)){
			//loome andmebaasiühenduse
			$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
			//määrame suhtlemisel kasutatava kooditabeli
			$conn->set_charset("utf8");
			//valmistame ette SQL keeles päringu
			$stmt = $conn->prepare("INSERT INTO vp_daycomment (comment, grade) VALUES(?,?)");
			echo $conn->error;
			//seome SQL päringu päris andmetega
			//määrata andmetüübid i - integer(täisarv) d - decimal(murdarv) s - string(tekst)
			$stmt->bind_param("si", $comment, $grade);
			if($stmt->execute()) {
				$grade = 7;
			}
			echo $stmt->error;
			//aitab sellest käsust $stmt->close();
			$stmt->close();
			//sulgeme andmebaasiühenduse
			$conn->close();
		}
	}
	#lühike versioon sisse logimisest
	$login_error = null;
	if(isset($_POST["login_submit"])) {
	$login_error = sign_in($_POST["email_input"], $_POST["password_input"]);
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<a href="https://greeny.cs.tlu.ee/~rinde/vp_2022/vp_banner_gs.png"><img src="pildid/vp_banner_gs.png" alt="Veebiprogrammeerimine banner"></a>
	<title><?php echo $author_name; ?>, veebiprogrammeerimine</title>
</head>
<body>
	<h1><?php echo $author_name; ?>, veebiprogrammeerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist infot.</p>
	<hr>
	<h2>Logi sisse</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<input type="email" name="email_input" placeholder="Kasutajatunnus ehk e-post">
		<input type="password" name="password_input" placeholder="salasõna">
		<input type="submit" name="login_submit" value="Logi sisse"><span><strong><?php echo $login_error; ?></strong></span>
	</form>
	<p><a href="add_user.php">Loo omale kasutaja</a></p>
	<hr>
	<p>Õppetöö toimus <a href="https://www.tlu.ee">Tallinna Ülikoolis</a>, Digitehnoloogiate instituudis.</p>
	<p>Lehe avamise hetk: <?php echo $weekday_names_et[$weekday_now - 1] .", " .$full_time_now; ?>.</p>
	<p>Praegu on <?php echo $part_of_day; ?>.</p>
	<!--päeva kommentaaride lisamise vorm-->
	<form method='POST'>
		<hr>
		<label for="comment_input">Kommentaar tänase päeva kohta:</label>
		<br>
		<textarea id="comment_input" name="comment_input" cols="70" rows="2" placeholder="kommentaar"></textarea>
		<br>
		<label for="grade_input">Hinne tänasele päevale (0...10):</label>
		<input type="number" id="grade_input" name="grade_input" min="0" max="10" step="1" value="7">
		<br>
		<input type="submit" id="comment_submit" name="comment_submit" value="Salvesta">
		<span><?php echo $comment_error; ?>
	</form>
	<hr>
	<p>Mõttetera tänasesse päeva: "<?php echo $random_quote_et[mt_rand(0, count($random_quote_et) - 1)]; ?>"</p>
	<!--Siin on väike omadussõnade vorm-->
	<form method="POST">
		<input type="text" id="todays_adjective_input" name="todays_adjective_input" placeholder="omadussõna tänase kohta">
		<input type="submit" id="todays_adjective_submit" name="todays_adjective_submit" value="Saada omadussõna">
	</form>
	<?php echo $adjective_html; ?>
	<p>Semester edeneb: <?php echo $from_semester_begin_days ."/" .$semester_duration_days; ?></p>
	<a href="https://www.tlu.ee"><img src="pildid/tlu_39.jpg" alt="Tallinna Ülikooli õppehoone"></a>
	<p>Minu nimi on Katriin, ma olen 21-aastane ja õpin Tallinna Ülikoolis informaatikat, digitaalse meedia suunal.</p>
	<form method="POST">
		<select id="photo_select" name="photo_select">
			<?php echo $select_html; ?>
		</select>
		<input type="submit" id="photo_submit" name="photo_submit" value="OK">
	</form>
	<hr>
	<?php
		echo $photo_html;
		require_once "footer.php";
	?>
</body>
</html<
