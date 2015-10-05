<?php
//set CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: false');
header('Access-Control-Allow-Methods: GET');

//discover Tent entity
if(!isset($_GET['entity'])){
	header('HTTP/1.0 400 Bad Request');
	header('Link: <?entity=some-entity-uri>, rel="Usage";');
	include('readme.html');
	die();
}
$entity = urldecode($_GET['entity']);
$ch = curl_init($entity);
curl_setopt_array($ch, array(
	CURLOPT_HEADER => TRUE,
	CURLOPT_RETURNTRANSFER => TRUE
));
$page = curl_exec($ch);
$header_re = 'Link: <([^>]*)>; rel="https://tent\.io/rels/meta-post"';
$body_re = '<link href="([^"]*)" rel="https://tent.io/rels/meta-post"/>';
$full_re = '$(?:'.$header_re.')|(?:'.$body_re.')$';
$matches = array();
preg_match_all($full_re, $page, $matches);
$links = array();
foreach($matches[1] as $match){
	if($match != ""){

		$links[] = $match;
	}
}
foreach($matches[2] as $match){
	if($match != ""){
		$links[] = $match;
	}
}
$ch = curl_init();
curl_setopt_array($ch, array(
	CURLOPT_RETURNTRANSFER => TRUE,
	CURLOPT_FAILONERROR => TRUE
));
$valid_meta_found = false;
foreach($links as $link){
	if($link[0] == '/'){
		$link = $entity . $link;
	}
	curl_setopt($ch, CURLOPT_URL, $link);
	$meta = curl_exec($ch);
	//$meta === false means curl_exec failed, otherwise it is a string
	//continue to check next link, if any
	if($meta===false) continue;
	//if $meta doesn't start with { it is not a valid Tent meta post
	if($meta[0] != '{') continue;
	json_decode($meta);
	//if json_decode gave an error, $meta is not a valid Tent meta post
	if(json_last_error() != JSON_ERROR_NONE) continue;
	$valid_meta_found = true;
	break;
}
if($valid_meta_found){
	echo($meta);
} else {
	header('HTTP/1.0 404 Not Found');
}
?>