<?php

class WSServer {

    public $server;

    public function __construct() {

        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        require (__DIR__ . '/lib/SplClassLoader.php');

        $classLoader = new SplClassLoader('WebSocket', __DIR__ . '/lib');
        $classLoader -> register();
        
        
        
        $this -> server = new \WebSocket\Server('127.0.0.1', 7000, false);
        // host,port,ssl

        // server settings:
        $this -> server -> setCheckOrigin(true);
        $this -> server -> setAllowedOrigin('foo.lh');
        $this -> server -> setMaxClients(100);
        $this -> server -> setMaxConnectionsPerIp(20);
        $this -> server -> setMaxRequestsPerMinute(1000);

        $this -> server -> registerApplication('demo', \WebSocket\Application\DemoApplication::getInstance());
        $this -> server -> run();
    }
}