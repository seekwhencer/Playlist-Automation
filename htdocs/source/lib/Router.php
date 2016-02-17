<?

	class Router {
		
		
		
		var $route = false;

		public function __construct(){
			include_once('source/conf/route.php');
		}

		public function getRoute(){
			return $this->route;
		}
		
		public function getSlugs(){
			return array_keys($this->route);
		}
		
		public function getPageSlug(){
			global $_c;
			$query 	= $_c->getUriQuery();
			$stack	= $_c->getUriPages();
			$slugs	= $this->orderSlugs($this->getSlugs());
			
			foreach($slugs as $slug){
				$cut = substr($query, 0,strlen($slug));
				if( $cut == $slug){
					return $cut;
				}
			}
			
			
			return false;
		}
		
		public function orderSlugs($slugs){
			$arr = array();
			foreach($slugs as $slug){
				$key 		= strlen($slug).''.$slug;
				$arr[$key] 	= $slug;
			}
			ksort($arr, SORT_NUMERIC);
			return array_reverse(array_values($arr));
		}
		
		public function getPageRoot(){
			global $_c;
			$stack	= $_c->getUriPages();
			return $this->route[$stack[0]];
		}
		
		public function getRouteOptions($pos=false){
			global $_c;
			$query 	= $_c->getUriQuery();
			$slug	= $this->getPageSlug();
			$cut 	= substr($query,strlen($slug)+1);
			$split	= explode('/',$cut);
			if($pos===false){
				return $split;
			} else {
				return $split[$pos];
			}
		}				
	
		
	}
	
	
	
?>