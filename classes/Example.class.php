<?php
	class Example {
		//muutujad, klassis nimetatakse neid omadusteks ehk properties
		//privaatsed ja avalikud
		private $secret_value;
		public $known_value = 7;
		private $received_value;
		
		//funktsioonid, klassis nimetatakse neid meetodid ehk methods
		
		
		//eriline funkstioon/meetod on konstruktor, mis käivitub klassi kasutusevõtul kohe, üks kord
		function __construct($value) {
			//siin sees peab ära tegema kõik asjad, mida soovime, et klassi käivitudes teeks
			echo "Klass käivitus!" ."<br>";
			// viitan klassi sees klassi enda muutujale
			$this->secret_value = mt_rand(1,10);
			echo "Salajane väärtus on: " .$this->secret_value ."<br>";
			$this->received_value = $value;
			$this->multiply();
		
		}
		//destuktor, 
		function __destruct() {
			echo "Klass lõpetas, ongi kõik!";
		}
		
		private function multiply() {
			echo "Privaatsete väärtuste korrutis on: " .$this->secret_value * $this->received_value ."<br>";
		}
		
		public function add() {
			echo "Privaatsete väärtuste summa on: " .$this->secret_value + $this->received_value ."<br>";
		}
		
	}