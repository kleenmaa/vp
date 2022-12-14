<?php
	$id = null;
	$type = "image/png";
	$output = "pildid/missing.png";
	#$privacy = 3;

	if(isset($_GET["photo"]) and !empty($_GET["photo"])){
		$id = filter_var($_GET["photo"], FILTER_VALIDATE_INT);
	}

	if(!empty($id)){
		require_once "../../config.php";
		$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT filename from vp_userprofilephotos WHERE id = ? AND deleted is NULL");
		echo $conn->error;
		$stmt->bind_param("i", $id);
		$stmt->bind_result($filename_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$output = $gallery_photo_normal_folder .$filename_from_db;
			$check = getimagesize($output);
			$type = $check["mime"];
		}
		$stmt->close();
		$conn->close();
	}

	//väljastan pildi
	header("Content-type: " .$type);
	readfile($output);
