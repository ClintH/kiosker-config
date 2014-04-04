<?php
	// Appends lines to file and makes sure the file doesn't grow too much
function append_line_to_limited_text_file($text, $filename) {
	if (!file_exists($filename)) { touch($filename); chmod($filename, 0666); }
	if (filesize($filename) > 2*1024*1024) {
		$filename2 = "$filename.old";
		if (file_exists($filename2)) unlink($filename2);
		rename($filename, $filename2);
		touch($filename); chmod($filename,0666);
	}
	if (!is_writable($filename)) die("Cannot open log file ($filename)");
	if (!$handle = fopen($filename, 'a')) die("Cannot open file ($filename)");
	if (fwrite($handle, $text) === FALSE) die("Cannot write to file ($filename)");
	fclose($handle);
}
date_default_timezone_set('Europe/Copenhagen');

$device = $_GET["d"];
$ip = $_GET[""];
$version = $_GET["v"];
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$random = $_GET["r"];

append_line_to_limited_text_file(date("c")."\t".$device."\t".$version."\t".$ip."\r\n", "ping.txt")
?>
{
	command: "noop",
	random: <?php echo($random); ?>
}
<?php

?>