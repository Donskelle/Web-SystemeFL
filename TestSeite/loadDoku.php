<?php

/**
 * Description of loadDoku
 * mittels disem Load wird die HTML informationen des Dokuments geladen und ausgegeben 
 * @author Peter Steensen
 */
class loadDoku{
	$target = '/var/www/Sphinx'; 
	$weeds = array('.', '..'); 
    /**
     * Liest die Datei voila.html und 
     * gibt sie zurück in die voila.html im Browser
     */
    public function __construct() {
            
    }
	private function loadDokuDir($DokuGroup){
		
		$directories = array_diff(scandir($target), $weeds); 
		foreach($directories as $value) 
		{	
			if(is_dir($target.$value)) 
			{   
				$directori = scandir($target.$value, $weeds);
				foreach ($directori as $datei) {
					 if(is_dir($target.$value.$datei)) 
					 {
						if($datei === "build"){
							
						}
					 }     
				}
			}
		}
	}
	private function echoTheDoku($DokuName){
		$lParth = $target.$DokuName."/build/html";
		if(is_dir(lParth)) {
			$directori = scandir(lParth);
		}			 
	}
	
	private function echoHTML($Path){
		$HTML = fopen($Path.".html", "r");
		header("Content-type: text/html");
        while (!feof($HTML)) {
            echo fgets($HTML);
        }
        fclose($HTML);
	}
}
new loadDoku();
?>