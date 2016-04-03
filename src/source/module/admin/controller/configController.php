<?

    class configController{
        
        protected $Config;
        
        public function __construct(){
            include_once('source/lib/Config.php');
            $this -> Config = new Config();
        }
        
        public function indexAction(){
            global $_p;    
            $_p->addStyle('admin.css');
            $_p->addScript('admin.js');
            $_p->addScript('admin_config.js');
            
            $params = getParams('configForm');
            if(is_array($params)){
                $this->Config->saveConfig();
            }
            
            
            $this -> Config -> keysort();
            
            return array(
                'config' => $this -> Config -> getFull()
            );
        }
        
    }
