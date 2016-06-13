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
        
        $npShowName = $Radio->getNowPlaylingShow();
        $npSongName = $Radio->getNowPlayingSong();
        
        foreach($Radio->Show as $s){
            if($s['slug']==$npShowName){
                $now_playing_show = $s;
            }
        }
                   
        $return = array(
            'show' => $now_playing_show['name'],
            'song' => $npSongName
        );
        
        echo json_encode($return);
    }
    
    public function nextsongAction(){
        shell_exec('mpc next');
    }
}
?>