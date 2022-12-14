<?php
	require_once "../../config.php";

	function sign_in($email, $password){
		$login_error = null;
		//globaalseid muutujaid hoitakse massiivis $GLOBALS
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT password FROM vp_users WHERE email = ?");
        echo $conn->error;
        $stmt->bind_param("s", $email);
        $stmt->bind_result($password_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //kasutaja on olemas, parool tuli ...
            if(password_verify($password, $password_from_db)){
				$stmt->close();
				$stmt = $conn->prepare("SELECT id, firstname, lastname FROM vp_users WHERE email = ?");
				$stmt->bind_param("s", $email);
				$stmt->bind_result($id_from_db, $firstname_from_db, $lastname_from_db);
				$stmt->execute();
				if($stmt->fetch()){
					//parool õige, oleme sees!
					//määran sessioonimuutujad
					$_SESSION["user_id"] = $id_from_db;
					$_SESSION["firstname"] = $firstname_from_db;
					$_SESSION["lastname"] = $lastname_from_db;

					//määrame värvid
					$_SESSION["user_bg_color"] = "#FBFDE1";
					$_SESSION["user_txt_color"] = "#000000";


				$stmt->close();
				$stmt = $conn->prepare("SELECT description, bgcolor, txtcolor FROM vp_userprofiles WHERE id = (SELECT MAX(id) FROM vp_userprofiles WHERE userid = ?)");
				echo $conn->error;
				$stmt->bind_param("i", $id_from_db);
				$stmt->bind_result($description_from_db, $bgcolor_from_db, $txtcolor_from_db);
				$stmt->execute();
				if($stmt->fetch()){
					if(!empty($bgcolor_from_db)){
						$_SESSION["user_bg_color"] = $bgcolor_from_db;
					}
					if(!empty($txtcolor_from_db)){
						$_SESSION["user_txt_color"] = $txtcolor_from_db;
					}
				}
						//määrame värvid
					//värvide profiilist lugemine, kui on, tulevad uued väärtused, kui pole, jäävad need mis otse kirjas

					$stmt->close();
					$conn->close();
					header("Location: home.php");
					exit();
				} else {
					$login_error = "Sisselogimisel tekkis tõrge!";
				}
            } else {
                $login_error = "Kasutajatunnus või salasõna oli vale!";
            }
        } else {
            $login_error = "Kasutajatunnus või salasõna oli vale!";
        }

        $stmt->close();
        $conn->close();

		return $login_error;
	}

	function sign_up($first_name, $last_name, $birth_date, $gender, $email, $password){
		$notice = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM vp_users WHERE email = ?");
		echo $conn->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = 2;
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO vp_users (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
			echo $conn->error;
			//krüpteerime salasõna
			$pwd_hash = password_hash($password, PASSWORD_DEFAULT);
			$stmt->bind_param("sssiss", $first_name, $last_name, $birth_date, $gender, $email, $pwd_hash);
			if($stmt->execute()){
				$notice = 1;
			} else {
				$notice = 3;
			}
		}
		//echo $stmt->error;
		$stmt->close();
		$conn->close();
		return $notice;
	}



		function read_user_description(){
			//kui profiil on olemas, loeb kasutaja lühitutvustuse
			$description = null;
			$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
			$conn->set_charset("utf8");
			//vaatame, kas on profiil olemas
			$stmt = $conn->prepare("SELECT description FROM vp_userprofiles WHERE userid = ?");
			echo $conn->error;
			$stmt->bind_param("i", $_SESSION["user_id"]);
			$stmt->bind_result($description_from_db);
			$stmt->execute();
			if($stmt->fetch()){
				$description = $description_from_db;
			}
			$stmt->close();
			$conn->close();
			return $description;
		}

		function store_user_profile($description, $bg_color, $txt_color){
			$notice = null;
			$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
			$conn->set_charset("utf8");
			//vaatame, kas on profiil olemas
			$stmt = $conn->prepare("SELECT id FROM vp_userprofiles WHERE userid = ?");
			echo $conn->error;
			$stmt->bind_param("i", $_SESSION["user_id"]);
			$stmt->bind_result($id_from_db);
			$stmt->execute();
			if($stmt->fetch()){
				$stmt->close();
				//uuendame profiili
				$stmt= $conn->prepare("UPDATE vp_userprofiles SET description = ?, bgcolor = ?, txtcolor = ? WHERE userid = ?");
				echo $conn->error;
				$stmt->bind_param("sssi", $description, $bg_color, $txt_color, $_SESSION["user_id"]);
			} else {
				$stmt->close();
				//tekitame uue profiili
				$stmt = $conn->prepare("INSERT INTO vp_userprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
				echo $conn->error;
				$stmt->bind_param("isss", $_SESSION["user_id"], $description, $bg_color, $txt_color);
			}
			if($stmt->execute()){
				$_SESSION["user_bg_color"] = $bg_color;
				$_SESSION["user_txt_color"] = $txt_color;
				$notice = "Profiil salvestatud!";
			} else {
				$notice = "Profiili salvestamisel tekkis viga: " .$stmt->error;
			}
			$stmt->close();
			$conn->close();
			return $notice;
		}
