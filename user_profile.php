<?php

	require_once "../../config.php";
	require_once "fnc_user.php";
	require_once "fnc_general.php";
	require_once "fnc_photo_upload.php";
	require_once "classes/Photoupload.class.php";
	require_once "fnc_gallery.php";

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

	$file_type = null;
	$photo_error = null;
	//$file_name = null;

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["photo_submit"])){
			#$alt = test_input($_POST["alt_input"]);
			#$privacy = filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT);

			if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
				$upload = new Photoupload($_FILES["photo_input"]);
				if(empty($upload->error)){
					$upload->check_file_size($photo_file_size_limit);
				}
				if(empty($upload->error)){
					$upload->create_filename($photo_name_prefix);
				}
				if(empty($upload->error)){
					$upload->resize_photo($normal_photo_max_w, $normal_photo_max_h);
					//lisan vesimärgi
					#$upload->add_watermark($watermark);
					$upload->save_photo($gallery_photo_normal_folder .$upload->file_name);
				}

				if(empty($upload->error)){
					$upload->resize_photo($thumbnail_photo_w, $thumbnail_photo_h, false);
					$upload->save_photo($gallery_photo_thumbnail_folder .$upload->file_name);
				}

				if(empty($upload->error)){
					//echo $gallery_photo_original_folder .$upload->file_name;
					$upload->move_original_photo($gallery_photo_original_folder .$upload->file_name);
				}

				if(empty($upload->error)){
					$photo_error = store_profile_picture($upload->file_name);
				}

				if(empty($photo->error) and empty($upload->error)){
					$photo_error = "Pilt edukalt üles laetud!";
				} else {
					$photo_error .= $upload->error;
				}
				unset($upload);
			} else {
				$photo_error = "Pildifail on valimata!";
			}


			//if empty error
		}//if photo_submit
	}//if POST

	/*$user_description = null;
	$bg_color_error = null;
	$user_description_error = null;
	$txt_color_error = null;
	*/
	$description = null;
	$notice = null;

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["profile_submit"])){
			$description = test_input($_POST["description_input"]);
			$new_bg_color = test_input($_POST["bg_color_input"]);
			$new_txt_color = test_input($_POST["txt_color_input"]);
			$notice = store_user_profile($description, $new_bg_color, $new_txt_color);
		}//if profile_submit
	}//if method==POST

	$description = read_user_description();

	require_once "header.php";

	echo "<p>Sisse loginud: " .$_SESSION["firstname"] ." " .$_SESSION["lastname"] .".</p> \n";



	/*if(isset($_POST["user_profile_submit"])){
		if(isset($_POST["bg_color_input"]) and !empty($_POST["bg_color_input"])) {
			$_SESSION["user_bg_color"] = $_POST["bg_color_input"];
		} else {
			$bg_color_error = "Taustavärvi muutmisega tekkis probleem!";
		}

		if(isset($_POST["txt_color_input"]) and !empty($_POST["txt_color_input"])) {
			$_SESSION["user_txt_color"] = $_POST["txt_color_input"];
		} else {
			$txt_color_error = "Tekstivärvi muutmisega tekkis probleem!";
		}

		if(isset($_POST["user_description_input"]) and !empty($_POST["user_description_input"])) {
			$_SESSION["user_description"] = $_POST["user_description_input"];
		} else {
			$user_description_error = "Lühikirjelduse lisamisega tekkis probleem!";
		}
	}

	if(empty($bg_color_error and $txt_color_error and $user_description_error)){
			//loome andmebaasiühenduse
			$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
			//määrame suhtlemisel kasutatava kooditabeli
			$conn->set_charset("utf8");
			//valmistame ette SQL keeles päringu
			/*$stmt = $conn->prepare("SELECT id FROM vp_userprofiles WHERE userid = ?");
			$stmt->bind_param("i", $id);
			$stmt->bind_result($userid_from_db);
			$stmt->execute();
			if($stmt->fetch()){
				if($userid_from_db == $_SESSION["user_id"]){
					$stmt->close();
					$stmt = $conn->prepare("UPDATE vp_userprofiles SET alttext = ?, privacy = ? WHERE id = ?");
					echo $conn->error;
					$stmt->bind_param("sii", $alt, $privacy, $id);
					if($stmt->execute() == false){
						$photo_error = 1;
					}
					$stmt->close();
					$conn->close();
					return $photo_error;
					exit();
				}
			}
			header("Location: gallery_own.php");
			exit();
		} */
/*
			$stmt = $conn->prepare("INSERT INTO vp_userprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
			echo $conn->error;
			//seome SQL päringu päris andmetega
			//määrata andmetüübid i - integer(täisarv) d - decimal(murdarv) s - string(tekst)
			$stmt->bind_param("isss", $_SESSION["user_id"], $_SESSION["user_description"], $_SESSION["user_bg_color"], $_SESSION["user_txt_color"]);
			if ($stmt->execute()) {
				$user_description = null;
			}
			echo $stmt->error;
			$stmt->close();
			$conn->close();
		} */

?>
<ul>
	<li><a href="?logout=1">Logi välja</a></li>
	<li><a href="home.php">Avalehele</a></li>
</ul>

<h2>Muuda/loo profiil</h2>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="bg_color_input">Vali taustavärv</label>
	<input type="color" name="bg_color_input" id="bg_color_input" placeholder="taustavärv" value="<?php echo $_SESSION["user_bg_color"]; ?>">
	<br>
	<label for="txt_color_input">Vali tekstivärv</label>
	<input type="color" name="txt_color_input" id="txt_color_input" placeholder="tekstivärv" value="<?php echo $_SESSION["user_txt_color"]; ?>">
	<br>
	<br>
	<label for="description_input">Minu lühikirjeldus:</label>
	<br>
	<textarea name="description_input" id="description_input" rows="10" cols="80" placeholder="Minu lühikirjeldus..."><?php echo $description; ?></textarea>
	<br>
	<input type="submit" name="profile_submit" value="Salvesta">
</form>
<br>
<span><?php echo $notice; ?></span>

<h2>Profiilipildi üleslaadimine</h2>
<p>Praegune profiilipilt:</p>
<div class="gallery">
<?php echo show_profile_picture(); ?>
</div>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	<label for="photo_input">Vali pildifail: </label>
	<input type="file" name="photo_input" id="photo_input">
	<br>
	<input type="submit" name="photo_submit" id="photo_submit" value="Lae üles">
	<span><?php echo $photo_error; ?></span>
</form>

<?php require_once "footer.php"; ?>
