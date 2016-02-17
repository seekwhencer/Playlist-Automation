<?php

	return array(
	
		'debug'					=>	false,
		
		'page_base'				=> 	'http://'.$_SERVER['HTTP_HOST'].''.str_replace('index.php','',$_SERVER['PHP_SELF']),
		'page_name'				=>	'NFS One', 
		'page_claim'			=>	'Radio',
		'page_domain'			=>	$_SERVER['HTTP_HOST'],
		'page_date'				=>	'',
		
		'layout'				=>	'radio',
		'frontend_url'			=>	'http://'.$_SERVER['HTTP_HOST'],
		
		'path_data'			    => 'data/',
        'station_config'        => 'station_config.json',
		
		'user_secret'           =>  '123456',
		
        'captcha_key_public'    =>  '',
        'captcha_key_private'   =>  '',
        

	);
