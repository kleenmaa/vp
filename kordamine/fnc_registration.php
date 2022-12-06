<?php

require_once "../../../config.php";

function registration($first_name, $last_name, $uliopilaskood){
		$notice = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM vp_registration WHERE uliopilaskood = ?");
		echo $conn->error;
		$stmt->bind_param("s", $uliopilaskood);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = 2;
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO vp_registration (uliopilaskood, eesnimi, perekonnanimi) VALUES(?,?,?)");
			echo $conn->error;
			$stmt->bind_param("sss",$uliopilaskood, $first_name, $last_name);
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
	
function count_registered(){
        $registered_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vp_registration WHERE tuhistanud IS NULL");
        echo $conn->error;
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $registered_count = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $registered_count;
    }
	
function count_paid(){
        $paid_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vp_registration WHERE maksnud IS NOT NULL");
        echo $conn->error;
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $paid_count = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $paid_count;
    }
	
	
function cancel_registration($uliopilaskood){
		$notice = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id FROM vp_registration WHERE uliopilaskood = ?");
		echo $conn->error;
		$stmt->bind_param("s", $uliopilaskood);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		echo $conn->error;
		if($stmt->fetch()){
			$stmt->close();
			$stmt = $conn->prepare("UPDATE vp_registration SET tuhistanud = now() WHERE uliopilaskood = ?");
			$stmt->bind_param("s", $uliopilaskood);
			if($stmt->execute()){
				$notice = 1;
			} else {
				$notice = 2;
			}
			$stmt->close();
			$conn->close();
			}
		}