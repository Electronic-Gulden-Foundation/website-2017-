<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
error_reporting(-1);

$result=file_get_contents("http://calc.egulden.org/s_poll.php");
echo $result;

$stamp=@file_get_contents("feed/stamp_premine");
if ($stamp!=date("dmYH")) {
	$premine=file_get_contents("http://calc.egulden.org/feed_premine.php");
	file_put_contents("feed/premine",$premine);
	$premine3=file_get_contents("http://calc.egulden.org/feed_premine3.php");
	file_put_contents("feed/premine3",$premine);
	file_put_contents("feed/stamp_premine",date("dmYH"));
	$peers=file_get_contents("http://calc.egulden.org/feed_peers5.php");
	file_put_contents("feed/peers5",$peers);
} else {
	$premine=file_get_contents("feed/premine");	
}
echo $premine; 
?>