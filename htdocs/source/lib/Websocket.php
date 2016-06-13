<?php

class Websocket {

    public $server;

    public function __construct() {
        $this->server = new \WebSocket\Server('127.0.0.1', 8000, false);
        // host,port,ssl

        // server settings:
        $this->server -> setCheckOrigin(true);
        $this->server -> setAllowedOrigin('foo.lh');
        $this->server -> setMaxClients(100);
        $this->server -> setMaxConnectionsPerIp(20);
        $this->server -> setMaxRequestsPerMinute(1000);

        $this->server -> registerApplication('demo', \WebSocket\Application\DemoApplication::getInstance());
        $this->server -> run();
    }

    /*
     public $options = array(
     'host' => 'localhost',
     'port' => 1414
     );

     public $sock, $sockets, $arClients;

     public function __construct($args = false) {
     error_reporting(E_ERROR);
     set_time_limit(0);

     if(is_array($args))
     $this -> setArgs($args);

     $this -> createServer();

     }

     public function setArgs($args) {
     $keys = array_keys($args);
     foreach ($keys as $key) {
     $this -> options[$key] = $args[$key];
     }
     }

     public function createServer($args = false) {

     if(is_array($args))
     $this -> setArgs($args);

     $this -> sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
     socket_bind($this -> sock, $this -> options['host'], $this -> options['port']);
     socket_listen($this -> sock);

     $this -> sockets = array($this -> sock);
     $this -> arClients = array();
     }

     public function run(){
     while (true) {

     echo "Warte auf Verbindung...rn";

     $sockets_change = $this -> sockets;
     $ready = socket_select($sockets_change, $write = null, $expect = null, null);

     echo "Verbindung angenommen.rn";

     foreach ($sockets_change as $s) {
     if ($s == $this -> sock) {
     $client = socket_accept($this -> sock);
     array_push($this -> sockets, $client);
     print_r($this -> sockets);
     } else {
     $bytes = @socket_recv($s, $buffer, 2048, 0);
     }
     }
     }
     }
     */
}
