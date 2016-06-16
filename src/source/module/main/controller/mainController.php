<?php

class mainController {

	public function indexAction() {
	    global $_p;
        
        $_p->addScript('page_home.js');
        
        include_once('source/module/admin/controller/scheduleController.php');
        $scheduleController = new scheduleController();
        
        $return = $scheduleController->silentAction();
        
		return $return;
	}
    
    public function heartbeatAction(){
        include_once('source/lib/Radio.php');
        $Radio = new Radio(
            array('silent'=>true)
        );
        
        $Radio->getShows();
        
        $npShow = $Radio->getNowPlaylingShow();
        $npSong = $Radio->getNowPlayingSong();
               
        $return = array(
            'show' => $npShow['name'],
            'song' => $npSong 
        );
        
        echo json_encode($return);
    }
    
    public function nextsongAction(){
        shell_exec('mpc next');
    }
}
?>