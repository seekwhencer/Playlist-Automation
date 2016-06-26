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
        // meta cycle    
        $today = strtotime("Today");
        $seconds = mktime() - $today;
        $diff = ($seconds+$this->Radio->Config->get('stream_meta_every_offset')) % $this->Radio->Config->get('stream_meta_every');
                
        if( $diff==0){
            $show = $this->Radio->getNowPlaylingShow();
            $response = $this -> Radio -> StreamMeta -> set(
                array(
                    'message'   => $this -> Radio -> Config -> get('station_name'). ' - NOW: ' . $show['name'].' . '.$show['meta'],
                    'start'     => mktime(), // timestamp, now
                    'duration'  => $this->Radio->Config->get('stream_meta_duration'), // show for x seconds 
                ),
                $this->Radio
            );
        }    
        
        // release meta on stream   
        $stream_meta = $this->Radio->StreamMeta->get($this->Radio);
        if($stream_meta !=false){
            $response = $this->Radio->StreamMeta->setMeta($stream_meta['message'],$this->Radio); 
        } else {
            $song = $this->Radio->getNowPlayingSong();
            $response = $this->Radio->StreamMeta->setMeta(trim($song['artist']).' - '.trim($song['title']),$this->Radio);
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