<?

    class stationController{
        
        
        
        public function __construct(){
            
        }
        
        public function indexAction(){
            global $_p;    
            $_p->addStyle('admin.css');
            $_p->addScript('admin.js');
            $_p->addScript('admin_station.js');
        }
        
    }
