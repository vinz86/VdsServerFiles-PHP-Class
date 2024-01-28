<?php

class VdsServerFiles {
	private $url;
	private $filename;
	private $file;
	private $unzip;
	private $deleteZipIfUnzip;

	public function __construct(){
	}


	public function download_file($url, $filename, $unzip=false, $deleteZipIfUnzip=false){
		if ((!$url) || (!$filename)) exit();

		set_time_limit(0);
		$file = fopen(dirname(__FILE__) . '/'.$filename, 'w+');
		$curl = curl_init($url);
		curl_setopt_array($curl, [
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FILE           => $file,
			CURLOPT_TIMEOUT        => 250,
			CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'
		]);
		$response = curl_exec($curl);
		if($response === false) {
			throw new \Exception('Curl error: ' . curl_error($curl));
		} else {
			echo "<br />Il file $url &egrave; stato salvato in modo corretto con il nome: $filename.";
		}
		$response;
		if ($unzip == true){
			$this->unzip_file($filename, $deleteZipIfUnzip);
		}
	}

	public function unzip_file($filename, $deleteZipIfUnzip = false){
		$unzip = new ZipArchive;
		$out = $unzip->open($filename);

		if ($out === TRUE) {

		  $unzip->extractTo(getcwd());
		  $unzip->close();
		  echo "<br />File $filename estratto.";

		  if ($deleteZipIfUnzip==true){
			if (unlink($filename)){
				echo "<br />File $filename eliminato.";
			} else {
				throw new \Exception("Errore durante l'eliminazione.");
			}
		  }

		} else {
		  throw new \Exception("Errore durante l'estrazione.");
		}
	}

}

$ServerFiles = new VdsServerFiles();
$ServerFiles->download_file("https://codeload.github.com/vinz86/Web-Server-ESP8266/zip/refs/heads/main", "file.zip", true, true);
?>
