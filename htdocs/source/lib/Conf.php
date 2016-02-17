<?
	class Conf {
			
		//
		protected $config;
		
		
		public function __construct(){
			$this->loadConfig();			
		}
		
		/**
		 * 
		 */
		public function get($key){
			if(!empty($key))
				if(array_key_exists($key, $this->config))
					return $this->config[$key];
		}
		
		/**
		 * 
		 */
		 public function loadConfig(){
		 	$this->config = include_once ('source/conf/app.php');
			foreach($this->config as $key=>$v){
				define(strtoupper($key),$v);
			}
		 }
		
	}
?>