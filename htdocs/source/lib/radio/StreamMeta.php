<?

class StreamMeta {
    
    /**
     * save a message file (streammeta.json) in the ramdisk
     */
    public function set($args,$Radio){
        echo '<pre>'.print_r($args,true).'</pre>';
        
        $fh = fopen($Radio->Config->get('path_ramdisk').'streammeta.json','w');
        fwrite($fh,json_encode($args,true));
        fclose($fh);
    }
    
    /*
     * read the streammeta.json from ramdisk
     * if the file is out of date by start and duration, it will be deleted
     * 
     */
    public function get($Radio){
        $stream_meta_path = $Radio->Config->get('path_ramdisk').'streammeta.json';        
        if(file_exists($stream_meta_path)){
            $stream_meta = json_decode(implode(file($stream_meta_path)),true);
            if( mktime() > ($stream_meta['start'] + $stream_meta['duration']) ){
                unlink($stream_meta_path);
            }
            return $stream_meta;
        }
        return false;
    }
    
    /**
     * set meta to stream
     */
    public function setMeta($message, $Radio){
        $icecast_set_meta_url = 'http://'.$Radio->Config->get('icecast_admin').':'.$Radio->Config->get('icecast_pass').'@'.$Radio->Config->get('icecast_host').':'.$Radio->Config->get('icecast_port').'/admin/metadata?mount=/'.$Radio->Config->get('icecast_endpoint').'&mode=updinfo&song='.urlencode($message);
        shell_exec('wget -qO- "'.$icecast_set_meta_url.'" &> /dev/null');                
        return $icecast_set_meta_url;
    }
    
    /*
     * read the icecast status file from ramdisk
     */
    public function getIcecast($Radio){
        $icecastStatusPath = $Radio->Config->get('path_ramdisk').'now_icecast_status.json';
        $file =  implode( file($icecastStatusPath) );
        $data = json_decode($file,true);
        
        $return = array(
            'title' => $data['icestats']['source']['title'],
            'listeners' => $data['icestats']['source']['listeners']
        );
        
        return $return;
    }
}