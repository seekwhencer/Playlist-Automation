<?php
	/* 
	   Abrisud 2015
	
	*/

	header('Content-type: text/html; charset=UTF-8');
	ini_set('session.use_trans_sid','off');
	
	session_start();
	
	//
	include_once ('source/functions.php');
    
    include_once('source/lib/Conf.php');
    include_once('source/lib/User.php');
    
    include_once('source/lib/Core.php');
    include_once('source/lib/Page.php');
    include_once('source/lib/Router.php');
    
    
    $_conf      = new Conf;
    $_u         = new User;
    $_c         = new Core;
    $_p         = new Page;
    $_router    = new Router;
    
    	
	$_c->process();
