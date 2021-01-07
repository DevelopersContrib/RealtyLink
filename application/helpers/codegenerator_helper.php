<?php

function generatecode($length = 12)
{
    $code = "";
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
    $maxlength = strlen($possible);

    if($length > $maxlength) {
        $length = $maxlength;
    }

    $i = 0; 

    while ($i < $length) { 
        $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        if (!strstr($code, $char)) { 
            $code .= $char;
            $i++;
        }
    }
    return $code;
}

function download_file($file, $name)
{
	$file = str_replace("index.php",'',$_SERVER['SCRIPT_FILENAME']).$file;

	if(!is_readable($file)) die('File not found or inaccessible!');
	$size = filesize($file);
	$name = rawurldecode($name);
	$known_mime_types=array(
		//"htm" => "text/html",
		//"zip" => "application/zip",
		"doc" => "application/msword",
		"docx" => "application/msword",
		"jpg" => "image/jpg",
		"xls" => "application/vnd.ms-excel",
		"xlsx" => "application/vnd.ms-excel",
		"csv" => "application/vnd.ms-excel",
		"ppt" => "application/vnd.ms-powerpoint",
		"gif" => "image/gif",
		"pdf" => "application/pdf",
		"txt" => "text/plain",
		//"html"=> "text/html",
		"png" => "image/png",
		"jpeg"=> "image/jpg"
	);

	$file_extension = strtolower(substr(strrchr($file,"."),1));
	if(array_key_exists($file_extension, $known_mime_types)){
		$mime_type=$known_mime_types[$file_extension];
	} else {
		//$mime_type="application/force-download";
		header("HTTP/1.0 404 Not Found");
		die();
	};

	@ob_end_clean();
	//if(ini_get('zlib.output_compression'))
	//ini_set('zlib.output_compression', 'Off');
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="'.$name.'"');
	header("Content-Transfer-Encoding: binary");
	header('Accept-Ranges: bytes');

	if(isset($_SERVER['HTTP_RANGE']))
	{
		list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
		list($range) = explode(",",$range,2);
		list($range, $range_end) = explode("-", $range);
		$range=intval($range);
		if(!$range_end) {
			$range_end=$size-1;
		} else {
			$range_end=intval($range_end);
		}

		$new_length = $range_end-$range+1;
		header("HTTP/1.1 206 Partial Content");
		header("Content-Length: $new_length");
		header("Content-Range: bytes $range-$range_end/$size");
	} else {
		$new_length=$size;
		header("Content-Length: ".$size);
	}

	$chunksize = 1*(1024*1024);
	$bytes_send = 0;
	if ($file = fopen($file, 'r'))
	{
		if(isset($_SERVER['HTTP_RANGE']))
		fseek($file, $range);

		while(!feof($file) &&
			(!connection_aborted()) &&
			($bytes_send<$new_length)
		)
		{
			$buffer = fread($file, $chunksize);
			echo($buffer);
			flush();
			$bytes_send += strlen($buffer);
		}
		fclose($file);
	} else
		die('Error - can not open file.');
	die();
}
?>