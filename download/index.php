<?php

$x=strtolower($_SERVER['QUERY_STRING']);

$trans=file("download");

foreach ($trans as $item) {
	$f=explode("#",trim($item));	
	if (strtolower($f[0])==$x) {
		header("location:{$f[1]}");
		die();
	}
}

header("location:http://www.egulden.org");

?>