<?php

class scheduleController {
    
    public $Radio;
    
    public function __construct(){
        include_once('source/lib/Radio.php');
        $this->Radio = new Radio();
    }

    public function indexAction() {
        
        global $_p;
        
        $_p->addStyle('admin.css');
        $_p->addScript('admin.js');
        $_p->addScript('admin_schedule.js');
        
        return array(
            'schedule' => $this -> Radio -> Schedule,
            'stations' => $this->Radio -> Station,
            'shows' => kfillBy($this->Radio -> Show, 'slug'),
            
        );
    }
    
    public function silentAction() {
        
        global $_p;
        
        return array(
            'schedule' => $this -> Radio -> Schedule,
            'stations' => $this->Radio -> Station,
            'shows' => kfillBy($this->Radio -> Show, 'slug'),
            
        );
    }
    
    public function saveAction(){
        $params = getParams();
        $this -> Radio -> saveSchedule($params['scheduleForm']);
    }
    
    public function deleteAction(){
        $params = getParams();
        $this -> Radio -> deleteSchedule($params['scheduleDelete']);
    }
    
    public function gettableAction(){
        return $this->indexAction();
    }
    
    public function cronAction(){
        $this -> Radio -> cronSchedule();
    }
    

}
?>