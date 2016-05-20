<?php

class mainController {

	public function indexAction() {
	    
        include_once('source/module/admin/controller/scheduleController.php');
        $scheduleController = new scheduleController();
        
        $return = $scheduleController->silentAction();
        
		return $return;
	}

}
?>