<?php

class showController {
    
    public $Radio;
    
    public function __construct(){
        include_once('source/lib/Radio.php');
        $this->Radio = new Radio();
    }

    public function indexAction() {
        
        global $_p;
        
        $_p->addStyle('admin.css');
        $_p->addScript('admin.js');
        $_p->addScript('admin_show.js');
        
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
    
    public function editAction(){
        $params = getParams();
        $showFileName = $params['show'];
        $show = $this->Radio->getShow($showFileName);
                
        return array(
            'stations' => $this->Radio->Station,
            'show' => $show,
            'folder' => array(
                 'music' => $this->Radio->getFolders(true,'music'),
                 'intro' => $this->Radio->getFolders(true,'intro'),
                 'spot' => $this->Radio->getFolders(true,'spot'),
                 'podcast' => $this->Radio->getFolders(true,'podcast')
             )
        );
    }
    
    public function saveAction(){
        global $_c;    
        $params = getParams('showForm');
        $params['timestamp'] = "".time();
        
        if($params['file_name']!=''){
            $oldFilePath = $this -> Radio -> Config -> get('path_show').$params['file_name'].'.json';
            if(file_exists($oldFilePath))
                unlink($oldFilePath);
        }
        
        $params['slug'] = $_c->convertToSlug($params['name']);
        
        $newFileName = md5(time());
        $fh = fopen( $this -> Radio -> Config -> get('path_show').$newFileName.'.json','w+');
        fwrite($fh, json_encode($params)); 
    }
    
    public function getlistingAction(){
        return $this->indexAction();
    }
    
    public function deleteAction(){
        $params = getParams();
        $show = $params['show'];
        $oldPath = $this -> Radio -> Config -> get('path_show').$show.'.json';
        if(file_exists($oldPath))
            unlink($oldPath);
    }
    
    public function duplicateAction(){
        $params = getParams();
        $show = $params['show'];
        $oldPath = $this -> Radio -> Config -> get('path_show').$show.'.json';
        $newPath = $this -> Radio -> Config -> get('path_show').md5(time()).'.json';
        
        if(file_exists($oldPath))
            copy($oldPath, $newPath);
    }
    
    public function previewAction(){
        $params = getParams();
        $showFileName = $params['show'];
        
        $show = $this -> Radio -> getShow($showFileName);
        $playlist = $this -> Radio -> buildPlaylist($show);
        
        shell_exec('sh /data/radio/script/schedule.sh');
        
        return array(
            'playlist' => $playlist
        );
    }

}
?>