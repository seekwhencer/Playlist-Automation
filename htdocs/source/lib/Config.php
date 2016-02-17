<?

    class Config {
        
        protected $data = array();
        
        public function __construct(){
            $this -> getConfig();
        }
        
        public function getConfig() {
            $filePath = PATH_DATA . "" . STATION_CONFIG;
            if (file_exists($filePath)) {
                $this -> data = json_decode(implode(file($filePath)), true);
            }
        }
        
        public function get($key){
            return $this->data[$key];
        }
        
        public function getFull(){
            return $this->data;
        }
        
        public function keysort(){
            ksort($this -> data);
        }
        
        public function saveConfig(){
            $params = getParams('configForm');
            $save = json_encode($params);
            $filePath = PATH_DATA . "" . STATION_CONFIG;
            $fh = fopen($filePath,'w+');
            fwrite($fh,$save);
            fclose($fh);
            
            $this->getConfig();
        }
        
    }
