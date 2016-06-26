<?php

$this->route = array(
			
    'home' => array(
    
        'label'			=>'Home',
        'slug'			=>'home',
        'module'		=>'main',
        'controller'	=>'main',
        'action'		=>'index',
        'view'			=>'home',
        'is_login'		=>'both',
        'navi'			=>'header',
        'navi_icon'		=>'home'
        								
    ),
    
    'heartbeat' => array(
        'label'         => 'Heartbeat',
        'module'        => 'main',
        'controller'    => 'main',
        'action'        => 'heartbeat',
        'view'          => 'heartbeat',
        'is_login'      => 'both',
        'is_xhr'        => true
    ),
    
    'nextsong' => array(
        'label'         => 'nextsong',
        'module'        => 'main',
        'controller'    => 'main',
        'action'        => 'nextsong',
        'view'          => 'nextsong',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
	'login' => array(
		'label'			=> 'Login',
		'module' 		=> 'user',
		'controller' 	=> 'user',
		'action' 		=> 'login',
		'view'			=> 'login',
		'is_login'		=> false,
	),
	
	'logout' => array(
		'module' 		=> 'user',
		'controller' 	=> 'user',
		'action' 		=> 'logout',
		'view'			=> 'logout',
		'is_login'		=> true,
	),

    
    /**
     *  Admin
     * 
     */
	'admin' => array(
		'label'			=> 'Admin',
		'module' 		=> 'admin',
		'controller' 	=> 'admin',
		'action' 		=> 'index',
		'view'			=> 'index',
		'is_login'		=> true,
	),
	
    /**
     *  Admin / Show
     * 
     */
    'admin/show' => array(
        'label'         => 'Admin Shows',
        'module'        => 'admin',
        'controller'    => 'show',
        'action'        => 'index',
        'view'          => 'index/show',
        'is_login'      => true,
    ),
	
    'admin/show/edit' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'show',
        'action'        => 'edit',
        'view'          => 'show/edit',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/show/save' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'show',
        'action'        => 'save',
        'view'          => 'show/save',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/show/getlisting' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'show',
        'action'        => 'getlisting',
        'view'          => 'show/getlisting',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/show/delete' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'show',
        'action'        => 'delete',
        'view'          => 'show/delete',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/show/duplicate' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'show',
        'action'        => 'duplicate',
        'view'          => 'show/duplicate',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/show/preview' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'show',
        'action'        => 'preview',
        'view'          => 'show/preview',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
	/**
     * Admin / Schedule
     * 
     */
	
	'admin/schedule' => array(
        'label'         => 'Admin Schedule',
        'module'        => 'admin',
        'controller'    => 'schedule',
        'action'        => 'index',
        'view'          => 'index/schedule',
        'is_login'      => true
    ),
    
    'admin/schedule/save' => array(
        'label'         => 'Admin Schedule',
        'module'        => 'admin',
        'controller'    => 'schedule',
        'action'        => 'save',
        'view'          => 'schedule/save',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/schedule/delete' => array(
        'label'         => 'Admin Schedule',
        'module'        => 'admin',
        'controller'    => 'schedule',
        'action'        => 'delete',
        'view'          => 'schedule/delete',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/schedule/gettable' => array(
        'label'         => 'Admin Schedule',
        'module'        => 'admin',
        'controller'    => 'schedule',
        'action'        => 'gettable',
        'view'          => 'schedule/gettable',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/schedule/cron' => array(
        'label'         => 'Admin Schedule',
        'module'        => 'admin',
        'controller'    => 'schedule',
        'action'        => 'cron',
        'view'          => 'schedule/cron',
        'is_login'      => "booth",
        'is_xhr'        => true
    ),
    
     /**
     * Admin / Podcast
     * 
     */
    
    'admin/podcast' => array(
        'label'         => 'Admin Podcast',
        'module'        => 'admin',
        'controller'    => 'podcast',
        'action'        => 'index',
        'view'          => 'index/podcast',
        'is_login'      => true
    ),
    'admin/podcast/edit' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'podcast',
        'action'        => 'edit',
        'view'          => 'podcast/edit',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/podcast/save' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'podcast',
        'action'        => 'save',
        'view'          => 'podcast/save',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/podcast/getlisting' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'podcast',
        'action'        => 'getlisting',
        'view'          => 'podcast/getlisting',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/podcast/delete' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'podcast',
        'action'        => 'delete',
        'view'          => 'podcast/delete',
        'is_login'      => true,
        'is_xhr'        => true
    ),
    
    'admin/podcast/cron' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'podcast',
        'action'        => 'cron',
        'view'          => 'podcast/cron',
        'is_login'      => 'both',
        'is_xhr'        => true
    ),
    
    /**
     *  Config
     * 
     */
     'admin/config' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'config',
        'action'        => 'index',
        'view'          => 'index/config',
        'is_login'      => true
    ),
    
      
    'admin/streammetacron' => array(
        'label'         => 'Admin Stream Meta Cronjob',
        'module'        => 'admin',
        'controller'    => 'admin',
        'action'        => 'streammetacron',
        'view'          => 'streammetacron',
        'is_login'      => 'both',
        'is_xhr'        => true
    ),
    
    /**
     *  Station
     * 
     */
     'admin/station' => array(
        'label'         => 'Admin',
        'module'        => 'admin',
        'controller'    => 'station',
        'action'        => 'index',
        'view'          => 'index/station',
        'is_login'      => true
    ),
    
	
);

?>