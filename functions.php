<?php 
function get_visitor_details(){
	require_once( 'geolocation.php' );
	
	$geoplugin = new geoPlugin();
	
	$geoplugin->locate();
	
	$result = array(
	'ip' => $geoplugin->ip,
	'country' => $geoplugin->countryName,
	'city' => $geoplugin->city
	);
	
	return $result;
}

function get_property($cond){
	require_once 'wc_rets.php';
	$exec = new wCRets();
	$output = $exec->get_data($cond);
	
	return $output;
}
