<?
    class podcastController {
            
        public $Podcast;
    
        public function __construct(){
            include_once('source/lib/Podcast.php');
            $this->Podcast = new Podcast();
        }
        
        public function indexAction(){
            global $_p;
        
            $_p->addStyle('admin.css');
            $_p->addScript('admin.js');
            $_p->addScript('admin_podcast.js');
            
            
            return array(
                'feeds' => $this->Podcast->Feed,
                'folder' => array(
                     'podcast' => $this->Podcast->getFolders(true,'podcast'),
                )
            );
            
        }
        
        public function editAction(){
            $params = getParams();
            $fileName = $params['podcast'];
            $podcast = $this->Podcast->getPodcast($fileName);

                    
            return array(
                'podcast' => $podcast,
                'folder' => array(
                     'podcast' => $this->Podcast->getFolders(),
                )
            );
        }
        
        public function saveAction(){
            global $_c;    
            $params = getParams('podcastForm');
            $params['timestamp'] = "".time();
            $params['slug'] = $_c->convertToSlug($params['name']);
            
            
            if($params['file_name']!=''){
                $oldFilePath = $this -> Podcast -> Config -> get('path_data_podcast').$params['file_name'].'.json';
                if(file_exists($oldFilePath))
                    unlink($oldFilePath);
                
                $oldUrlPath = $this -> Podcast -> Config -> get('path_data_podcast').$params['file_name'].'.url';
                if(file_exists($oldUrlPath))
                    unlink($oldUrlPath);
            } else {
                 mkdir($this->Podcast->Config -> get('path_podcast').'/'.$params['slug']);
                 $params['folder'] = $params['slug'];
            }
            
            
            $newFileName = md5(time());
            
            $fh = fopen( $this -> Podcast -> Config -> get('path_data_podcast').$newFileName.'.json','w+');
            fwrite($fh, json_encode($params));
            fclose($fh);
        }
        
        public function getlistingAction(){
            return $this->indexAction();
        }
        
        public function deleteAction(){
            $params = getParams();
            $podcast = $params['podcast'];
            $oldPath = $this -> Podcast -> Config -> get('path_data_podcast').$podcast.'.json';
            if(file_exists($oldPath))
                unlink($oldPath);
        }
        
        public function cronAction(){
            
            $feedSource = array();   
            foreach($this->Podcast->Feed as $podcast){
                $feedSource[$podcast['folder']] = array();
                $xml_string = implode('',file($podcast['url']));
                $xmlIterator = new RecursiveIteratorIterator(
                    new SimpleXMLIterator($xml_string), RecursiveIteratorIterator::SELF_FIRST
                );
                foreach ($xmlIterator as $nodeName => $node) {
                    $n = json_decode(json_encode($node),true);
                    if(trim($n['@attributes']['url'])!=''){
                        $s = explode('/',$n['@attributes']['url']);
                        $ss = explode('?',$s[count($s)-1]);
                        if( count($ss) > 1) {
                            $fileName = $ss[0];
                        } else {
                            $fileName = $s[count($s)-1];
                        }
                        $feedSource[$podcast['folder']][] = array(
                            'url' => trim($n['@attributes']['url']),
                            'file_name' => $fileName
                        );
                    }
                }
            }
            
            // fetch it
            $cmd = array();
            foreach($feedSource as $folder => $fs){
                foreach($fs as $i){
                    $filePath = $this->Podcast->Config -> get('path_podcast').$folder.'/'.$i['file_name'];
                    
                    if(!file_exists($filePath)){
                        $file = file_get_contents($i['url']);
                        file_put_contents($filePath,$file);
                        echo 'fetched: '.$i['url']."<br/>\n";
                        //$cmd[] = 'wget -qO- '.$i['url'].' &> '.$filePath;
                    } else {
                        echo 'file exists: '.$i['url']."<br/>\n";
                    }
                }
            }
            
            //print_r($cmd);
        }
    }
