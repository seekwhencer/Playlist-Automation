<?

	class Core {
		
		var $partialcache = array();
		


		public function process() {
			global $_p, $_router, $_u;
			
			$_p->page_url 	= $this->getUriQuery();
			$_p->page_slug	= $_router->getPageSlug();
			$_p->page_root	= $_router->getPageRoot();
			
			
			// wenn falscher slug übrgeben wurde, redirect auf startseite
			if($_p->page_slug==false && $_p->page_url!='')
				redirect('');

					
			if($_p->page_slug=='')
				$_p->page_slug='home';
			
			$_p->content = $_router->route[$_p->page_slug];
			
			
			if( $_p->content['is_login'] === true && $_u->isLogin()==false )
				redirect('');

			
			// use module, controller, action
			if($_p->content['module'] !='' && $_p->content['controller']!='') {
				$module 	= $_p->content['module'];
				$controller	= $_p->content['controller'];
				$include	= 'source/module/'.$module.'/controller/'.$controller.'Controller.php';
				$classname	= $controller.'Controller';
				
				if(file_exists($include)){
					include_once($include);
					if(class_exists($classname)) {
						$con = new $classname;
						$act = $_p->content['action'].'Action';
						if($_p->content['action'] == ''){
							$ret = $con->indexAction();
						} elseif(method_exists($con,$act)) {
							$ret = $con->{$act}();
							
						}
					}
					$action = $ret;
				}
			}

			$action = $ret;
			
			
			if($_p->content['action']){
				$template_file 	= $_p->content['action'];
			}

			if($_p->content['view']){
				$template_file 	= $_p->content['view'];
			}

			$template_path	= './source/layout/'.LAYOUT.'/template/';
			
			
			if(!file_exists($template_path.$template_file.'.phtml')){
				$template_path = './source/module/'.$_p->content['module'].'/view/';
			}
			
			$_p->content['description'] = $this->partial($template_file,$action,$template_path);
				
			if($_p->content['label']!='') {
				if(trim(strip_tags($_p->title))) {
					$_p->setTitle( strip_tags($_p->title).' '.$_p->content['label'].' - '.PAGE_NAME.' - '.PAGE_CLAIM );
				} else {
					if($_p->content['label']=='Home'){
					    $_p->setTitle( PAGE_NAME.' - '.PAGE_CLAIM );
					} else {
    					$_p->setTitle( $_p->content['label'].' - '.PAGE_NAME.' - '.PAGE_CLAIM );
                    }
    			}
			} else {
				//$_p->setTitle('');
			}
		
		
			// Javascript
			include_once('source/conf/js.php');

			
			// Set Customized Page CSS File
			if(!empty($_p->content['css']))
				$_p->addStyle(LAYOUT."/".$_p->content['css'].'?'.time());			
            
            // base CSS
			include_once('source/conf/css.php');
			
					
			// last but not least...
			$_p->create();
			
			echo trim($_p->site); // ;)

		}
				
		public function getUriParams($i){
			$url = $this-> getUriPages();
 			return $url[count($url)-$i];
		}
		
		/**
		 * 
		 * 
		 */
		public function getUriPages($filename=false,$as_sring=false){
			
			if($filename==false) $filename = 'index.php';
			$filter = 'abcdefghijklmnopqrstuvwxyz0123456789@./+-_ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			
			$base	= str_replace($filename,'', urldecode($_SERVER['PHP_SELF']));
			
			if($base!='/') { 
				$uri	= str_replace($base, '', urldecode($_SERVER['REQUEST_URI']));
			} else {
				$uri	= substr(urldecode($_SERVER['REQUEST_URI']),1,strlen(urldecode($_SERVER['REQUEST_URI'])));
			}

			$ux = explode('?',$uri);
			if(count($ux>1))
				$uri=$ux[0];
			
			for($u=0; $u<strlen($uri); $u++){
				for($f=0; $f<strlen($filter); $f++){
					if($uri{$u}==$filter{$f}){
						$ub.=$uri{$u};
					}
				}
			}
			
			if($ub{strlen($ub)-1}=='/') $ub = substr($ub, 0, -1);
			return explode('/', $ub);
		}
		
		/**
		 * 
		 * 
		 */
		public function getUriQuery(){
			if($filename==false) $filename = 'index.php';
			$filter = 'abcdefghijklmnopqrstuvwxyz0123456789@./+-_ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			
			$base	= str_replace($filename,'', urldecode($_SERVER['PHP_SELF']));
			
			if($base!='/') { 
				$uri	= str_replace($base, '', urldecode($_SERVER['REQUEST_URI']));
			} else {
				$uri	= substr(urldecode($_SERVER['REQUEST_URI']),1,strlen(urldecode($_SERVER['REQUEST_URI'])));
			}

			$ux = explode('?',$uri);
			if(count($ux>1))
				$uri=$ux[0];
			
			for($u=0; $u<strlen($uri); $u++){
				for($f=0; $f<strlen($filter); $f++){
					if($uri{$u}==$filter{$f}){
						$ub.=$uri{$u};
					}
				}
			}
			
			if($ub{strlen($ub)-1}=='/') $ub = substr($ub, 0, -1);
			return trim($ub);
		}
		
		/**
		 * 
		 * 
		 */
		function partial($page,$action,$path=false){
			    
			global $_p;
            
           
			if(!$page)
				return;
				
			if($path==false){
				
                $file='./source/module/'.$_p->content['module'].'/view/'.$page.'.phtml';
                if(!file_exists($file)){
                    $file = './source/layout/'.LAYOUT.'/base/'.$page.'.phtml';
                }
			} else {
				$file = $path.$page.'.phtml';
			}
			
			$key 	= md5($file);
			
			if(file_exists($file)){
				if(!$this->partialcache[$key]){
					$source = implode(file($file));
					$this->partialcache[$key] = $source;
				}
				
				ob_start();
				eval("?>" . $this->partialcache[$key] . "<?");
				$c = ob_get_contents();
				ob_end_clean();
				return $c; 
			} 
		}

		/**
		 * 
		 * 
		 */
		public function preArr($array){
			return '<pre>'.print_r($array,true).'</pre>';
		}

		
		/**
		 * 
		 * 
		 */
		public function convertToSlug($string,$filter=false){
						
			$text = str_replace(array('ä','ü','ö','Ä','Ü','Ö'),array('ae','ue','oe','Ae','Ue','Oe'),$string);
		    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		    $text = trim($text, '-');
		    if (function_exists('iconv')){
		        $text = iconv('utf-8', 'ASCII//TRANSLIT', $text);
		    }
		    $text = strtolower($text);
		    $text = preg_replace('~[^-\w]+~', '', $text);
		    if (empty($text)) {
			    return false;
		    }
			
			if($filter==false)
				$filter = 'abcdefghijklmnopqrstuvwxyz0123456789/+-&?;';

			for($u=0; $u<strlen($text); $u++){
				for($f=0; $f<strlen($filter); $f++){
					if($text{$u}==$filter{$f}){
						$ub.=$text{$u};
					}
				}
			}
			
			if($ub{strlen($ub)-1}=='/') $ub = substr($ub, 0, -1);
			return $ub;
		}
		
	}

?>