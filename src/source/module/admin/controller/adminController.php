<?php

class adminController {
	
	public $Radio;
	
	public function __construct(){
		include_once('source/lib/Radio.php');
		$this->Radio = new Radio();
	}

	public function indexAction() {
        
        global $_p;
        
        $_p->addStyle('admin.css');
        $_p->addScript('admin.js');
        
		return array(
			'stations' => $this->Radio->Station,
			'shows' => $this->Radio->Show,
			'folder' => array(
			     'music' => $this->Radio->getFolders(true,'music'),
			     'intro' => $this->Radio->getFolders(true,'intro'),
			     'spot' => $this->Radio->getFolders(true,'spot'),
			     'podcast' => $this->Radio->getFolders(true,'podcast')
             )
		);
	}
    
    public function streammetacronAction(){
        $streammeta = $this->Radio->StreamMeta->get($this->Radio);
        if($streammeta !=false){
            
        }
        
    }
    
    /*    
    public function threadAction(){
        include_once('source/lib/WSServer.php');
        
        $options = array(
            'host' => 'localhost',
            'port' => 1414 
        );
        
        $WSServer = new WSServer($options);
        
        echo 'THREAD';
        
    }
     */

}
?>