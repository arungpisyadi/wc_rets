<?php

/**
*  WCRETS - PHP library for RETS
*  version 1.0.1
*  http:/wittycookie.com
*  Copyright (C) 2014 Arung Isyadi
*  please submit problem or error reports to https://github.com/SawatoriMibu
*
*  All rights reserved.
*  Permission is hereby granted, free of charge, to use, copy or modify this software.  Use at your own risk.
*
*  This library is a test for wrapper RETS specialized for wittycookie clients.
*
*/

require 'RETS.php'; 

class wCRets{
	
	//change the data below as necessary
	private $url = '<rets server including full path to login transaction>';
	private $user = '<user id>';
	private $password = '<user password>';
	private $useragent = '<application user-agent>';
	private $useragent_password = '<application password>';
	private $upload_dir = '<dir to hold transferred images of property; eg: "images/RETS/">';
	//private $url = ''; //might need this later
	
	public function get_meta_all(){
		
		$rets = new RETS();
		$rets->url = $this->url;
		$rets->user = $this->user;
		$rets->password = $this->password;
		$rets->useragent = $this->useragent;
		$rets->useragent_password = $this->useragent_password;
		
		$rets->Login();
		
		$response = $rets->GetMetadata('METADATA-RESOURCE','0');
		
		return $response;
		
		$rets->logout();
		
	}
	
	private function get_property_active($resource, $class, $query, $field, $num){
		
		$rets = new RETS();
		$rets->url = $this->url;
		$rets->user = $this->user;
		$rets->password = $this->password;
		$rets->useragent = $this->useragent;
		$rets->useragent_password = $this->useragent_password;
		
		$login = $rets->Login();
		
		if($login){
			$responses = $rets->GetDataArray($resource, $class, $query, $field, $num);
			
			//$object =  new stdClass();
			$num = count($responses);
			for($x = 0; $x < $num; $x++){
				$image = $this->upload_dir . $responses[$x]['sysid'] .'/' . $responses[$x]['sysid'] .'_1.jpg';
				
				if( !file_exists( $image )){
					$images = $this->upload_dir . $responses[$x]['sysid'] .'/';
					
					if( !file_exists( $images )){
						mkdir($images, 0777, true);
					}
					$img = $rets->GetPhoto('Property',$responses[$x]['sysid'].':*', $images);
					if( $img ){
						$display = $images . '/' . $responses[$x]['sysid'] .'_1.jpg';
					}
				}else{
					$images = $this->upload_dir . $responses[$x]['sysid'] .'/';
					$display = $images . '/' . $responses[$x]['sysid'] .'_1.jpg';
				}
				
				$array[$x]['sysid'] = $responses[$x]['sysid'];
				$array[$x]['type'] = $responses[$x]['1'];
				$array[$x]['address'] = $responses[$x]['14'];
				$array[$x]['zip'] = $responses[$x]['11'];
				$array[$x]['area'] = $responses[$x]['2283'];
				$array[$x]['region'] = $responses[$x]['3794'];
				$array[$x]['province'] = $responses[$x]['88'];
				$array[$x]['listPrice'] = $responses[$x]['226'];
				$array[$x]['remarks'] = $responses[$x]['411'];
				$array[$x]['dwelling'] = $responses[$x]['2733'];
				$array[$x]['style'] = $responses[$x]['14'];
				$array[$x]['built'] = $responses[$x]['16'];
				$array[$x]['acres'] = $responses[$x]['2453'];
				$array[$x]['hectares'] = $responses[$x]['2455'];
				$array[$x]['sq_ft'] = $responses[$x]['2457'];
				$array[$x]['sq_mtr'] = $responses[$x]['2460'];
				$array[$x]['bathrooms'] = $responses[$x]['3928'];
				$array[$x]['bedrooms'] = $responses[$x]['378'];
				$array[$x]['influence'] = $responses[$x]['3926'];
				$array[$x]['image'] = $display;
			}
			
			$output = json_decode(json_encode($array), false);
		}
		
		return $output;
		
		//$rets->logout();
		
	}
	
	public function get_data($cond, $Resource = '', $Class = '', $Query = '', $Fields = '', $Num = ''){
		//require_once 'wc_rets.php';
		//$exec = new wCRets();
		
		switch( $cond ){
			
			case 'meta-all':
				$result = $this->get_meta_all();
			
			case 'res-dettached':
				$result = $this->get_property_active('Property', '1', '(363=|A)', '', 20);
			break;
			
			case 'res-attached':
				$result = $this->get_property_active('Property', '2', '(363=|A)', '', 20);
			break;
	
			case 'res-land':
				$result = $this->get_property_active('Property', '3', '(363=|A)', '', 20);
			break;
	
			case 'res-mfamily':
				$result = $this->get_property_active('Property', '7', '(363=|A)', '', 20);
			break;
	
			case 'res-user':
				$result = $this->get_property_active('Property', '9', '(363=|A)', '', 20);
			break;
	
			case 'res-office':
				$result = $this->get_property_active('Property', '10', '(363=|A)', '', 20);
			break;
	
			case 'res-cross':
				$result = $this->get_property_active('Property', '11', '(363=|A)', '', 20);
			break;
			
		}
		
		return $result;
		
	}
	
}
