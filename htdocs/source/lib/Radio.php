<?

class Radio {

    public $Config;
    public $Station;
    public $Show;
    public $Schedule;
    public $Podcast;
    public $Intro;
    public $StreamMeta;

    public $Suffix = array('.mp3');
    public $Excludes = array('Thumbs.db', '.', '..');

    public function __construct($options = false) {
        include_once('source/lib/Config.php');
        $this -> Config = new Config();
        
        include_once('source/lib/radio/StreamMeta.php');
        $this -> StreamMeta = new StreamMeta();
        
        if($options===false){
            $this -> getStations();
            $this -> getShows();
            $this -> getSchedule();
            return;
        }
        
        if($options['silent']===true){
            return;
        }
    }


    public function getStations() {
        $path = $this -> Config -> get('path_data_station');
        $files = array();

        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && substr($entry, -4) == 'json') {
                    $files[] = $entry;
                }
            }
            closedir($handle);
        }

        foreach ($files as $fileName) {
            $file = file($path . $fileName);

            if (trim($file[0]) != '') {

                $data = json_decode(implode($file), true);
                $data['file_name'] = str_replace('.json', '', $fileName);
                $this -> Station[$data['file_name']] = $data;
            }
        }
        $this -> Station = ksortBy($this -> Station, 'name');
    }

    public function getShows() {
        $path = $this -> Config -> get('path_data_show');
        $files = array();
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && substr($entry, -4) == 'json') {
                    $files[] = $entry;
                }
            }
            closedir($handle);
        }

        foreach ($files as $fileName) {
            $file = file($path . $fileName);

            if (trim($file[0]) != '') {

                $data = json_decode(implode($file), true);
                $data['file_name'] = str_replace('.json', '', $fileName);
                $this -> Show[$data['file_name']] = $data;

            }
        }
        $this -> Show = ksortBy($this -> Show, 'timestamp');
        $this -> Show = kfillBy($this -> Show, 'file_name');

        $this -> Show = array_reverse($this -> Show);
    }

    public function getShow($fileName) {

        if ($this -> Show[$fileName])
            return $this -> Show[$fileName];

        return false;

    }

    public function getFolders($recursive = false, $use) {

        switch($use) {
            case 'music' :
                $path = $this -> Config -> get('path_music');
                break;

            case 'intro' :
                $path = $this -> Config -> get('path_intro');
                break;

            case 'spot' :
                $path = $this -> Config -> get('path_spot');
                break;
                
            case 'podcast' :
                $path = $this -> Config -> get('path_podcast');
                break;
        }

        $files = array();

        $folders = array();
        $objects = array();
        if(file_exists($path))
        
        if (!$recursive) {
            $objects = new DirectoryIterator($path);
        } else {
            $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        }

        $c = 0;
        foreach ($objects as $name => $object) {

            if ($object -> isDir() && !in_array($object -> getBasename(), $this -> Excludes)) {
                $folders[$object -> getPathname()] = str_replace($path, '', $object -> getPathname());
                $c++;
            }
        }

        ksort($folders);

        return $folders;
    }

    /**
     *
     *
     */
    public function buildPlaylist($show = false) {
        $dir = $this -> Config -> get('path_music').$show['folder'];

        // get the song files
        $files = $this -> searchFiles($dir, false, $show['recursive']);
 
        if (file_exists($this -> Config -> get('path_podcast').$show['podcast']['folder'])) {
            $podcast_files = array_values($this -> searchFiles($this -> Config -> get('path_podcast') . $show['podcast']['folder']));
            $count_podcast_files = count($podcast_files);
            if($count_podcast_files > 0 && $show['podcast']['cast_num'] > $count_podcast_files)
                $show['podcast']['cast_num'] = $count_podcast_files;
        }
        
        // get spot files
        if (file_exists($this -> Config -> get('path_spot').$show['spot']['folder'])) {
            $spot_files = array_values($this -> searchFiles($this -> Config -> get('path_spot').$show['spot']['folder']));
        }
        // get intro files
        if (file_exists($this -> Config -> get('path_intro').$show['intro']['folder'])) {
            $intro_files = array_values($this -> searchFiles($this -> Config -> get('path_intro').$show['intro']['folder']));
        }

        // build by using the latest files by global MAX_PLAYLIST_ENTRIES
        $playlist = array();
        $i = 0;
        foreach ($files as $f) {
            $playlist[] = str_replace('\\', '/', $f['path']) . '/' . $f['rname'];
            if ($i == $this -> Config -> get('max_files_per_playlist'))
                break;

            $i++;
        }

        // get hot rotation
        if ($show['hot_rotation']['song_num'] > 0 && $show['hot_rotation']['enable'] == '1') {
            $hot_rotation = $this->getHotRotation($show, $playlist);    

            // multiply the hot rotation
            $hot_multi = array();
            for ($i = 0; $i < intval($show['hot_rotation']['insert_multiplier']); $i++)
                $hot_multi = array_merge($hot_rotation, $hot_multi);

            // merge muliplied hot rotation with sorted playlist
            if($show['hot_rotation']['only']=='1'){
                $playlist = $hot_multi;
            } else {
                $playlist = array_merge($playlist, $hot_multi);
            }
            

        }

        // shake it
        if ($show['no_shuffle'] != 1) {
            shuffle($playlist);
        }

        // shake it range wise
        if ($show['shuffle_range'] > 0 && $show['no_shuffle'] == 1) {
            $pcount = count($playlist);
            $playlist_range_shuffle = array();

            for ($i = 0; $i < $pcount; $i++) {
                if ($i % $show['shuffle_range'] == 0) {
                    $p_range = array();
                    for ($k = $i; $k < ($i + $show['shuffle_range']); $k++) {
                        $p_range[] = $playlist[$k];
                    }
                    shuffle($p_range);
                    $playlist_range_shuffle = array_merge($playlist_range_shuffle, $p_range);
                }
            }
            $playlist = $playlist_range_shuffle;
        }

        // add hot rotation at the beginning
        if ($show['hot_rotation']['at_beginning'] == 1 && is_array($hot_rotation))
            $playlist = array_merge($hot_rotation, $playlist);

        // playlist with podcasts
        // if ($podcast != false) {
            
        if ($show['podcast']['folder'] && count($podcast_files) > 0){    
            $index = 0;
            $pnum = 0;
            
            // set the max random count
            if(count($podcast_files) < $show['podcast']['cast_num'])
                $show['podcast']['cast_num'] = count($podcast_files)-1;

            // add random podcast on top
            if ($show['podcast']['random_first'] == 1) {
                $index = rand(0, ($show['podcast']['cast_num'] - 1));
                array_unshift($playlist, $podcast_files[$index]['path'] . '/' . $podcast_files[$index]['filename']);
                $pnum = 1;
            }

            // build new playlist with podcasts
            $every_nth_song = $show['podcast']['every_nth_song'];
            if ($every_nth_song > 0) {
                $playlist_with_podcast = array();
                $i = 0;
                foreach ($playlist as $p) {
                    if (($i + ($every_nth_song - $show['podcast']['offset'])) % ($every_nth_song) === 0) {

                        $index = rand(0, $show['podcast']['cast_num'] - 1);

                        if ($show['podcast']['order_by_time_desc'] == 1) {
                            $index = $pnum % $show['podcast']['cast_num'];
                        }

                        if (($i > 0 && $show['podcast']['random_first'] == 1) || $show['podcast']['random_first'] != 1) {
                            $playlist_with_podcast[] = $podcast_files[$index]['path'] . '/' . $podcast_files[$index]['filename'];
                            $pnum++;
                        }
                    }

                    $playlist_with_podcast[] = $p;
                    $i++;
                }
                $playlist = $playlist_with_podcast;
            }
        }

        // add spots
        if (count($spot_files) > 0) {
            $playlist_with_spots = array();
            $spot_num = count($spot_files);
            $i = 0;
            foreach ($playlist as $p) {
                $index = rand(0, $spot_num - 1);
                if (($i + ($show['spot']['every_nth_song'] - $show['spot']['offset'])) % ($show['spot']['every_nth_song']) === 0) {
                    $playlist_with_spots[] = $spot_files[$index]['path'] . '/' . $spot_files[$index]['filename'];
                }
                $playlist_with_spots[] = $p;
                $i++;
            }
            $playlist = $playlist_with_spots;

        }

        // add opener
        if (count($intro_files) > 0) {
            $index = 0;
            if ($show['intro']['random'] == 1)
                $index = rand(0, count($intro_files) - 1);

            array_unshift($playlist, $intro_files[$index]['path'] . '/' . $intro_files[$index]['filename']);
        }

        // write the playlist file
        $fh = fopen($this -> Config -> get('path_data_playlist').$this -> Config -> get('playlist_name'), 'w');

        foreach ($playlist as $p) {

            $split = explode('/', $p);
            $filename = $split[count($split) - 1];
            unset($split[count($split) - 1]);

            $path = implode('/', $split);

            if (strpos($path, '://') !== false) {
                //$row = str_replace('\\', '/', $path) . '/' . ($filename) . "\r\n";
                $row = $path . '/' . ($filename) . "\r\n";
            } else {
                //$row = str_replace('/', '\\', ($path . '/' . mb_convert_encoding($filename, 'UTF-8', 'auto')) . "\r\n");
                $row = $path . '/' . mb_convert_encoding($filename, 'UTF-8', 'auto') . "\r\n";
            }

            //echo $row . "";

            fwrite($fh, $row);
        }
        fclose($fh);
        
        $this -> writeNowPlayingShow($show);
               
        return $playlist;
    }

    public function getHotRotation($show, $playlist){
        $hot_rotation = array();
        $i=0;
        foreach ($playlist as $p) {
            if ($i == $show['hot_rotation']['song_num'])
                break;

            $hot_rotation[] = $p;
            $i++;
        }

        // shuffle beginning hot rotation
        if ($show['hot_rotation']['shuffle_beginning'] == 1)
            shuffle($hot_rotation);
           
        return $hot_rotation;
    }

    public function searchFiles($dir, $search = false, $recursive = false) {
        if ($dir) {
            $path = ($dir);
        }

        $files = array();

        if (!$recursive) {
            $objects = new DirectoryIterator($path);
        } else {
            $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        }

        foreach ($objects as $name => $object) {
            $ok = false;
            if ($search != '')
                if (strpos(strtolower($object -> getFilename()), strtolower($search)) != false && !$object -> isDir() && !in_array($object -> getFilename(), $this -> Excludes))
                    $ok = true;

            if ($search == '')
                if (!$object -> isDir() && !in_array($object -> getFilename(), $this -> Excludes))
                    $ok = true;

            if ($ok == true) {
                $name = utf8_encode($object -> getFilename());

                $files[] = array('name' => str_replace($this -> Suffix, '', $name), 'rname' => utf8_encode($object -> getFilename()), 'filename' => utf8_encode($object -> getFilename()), 'path' => $object -> getPath(), 'ts_edit' => 'te' . $object -> getMTime() . microtime(), 'ts_make' => 'tm' . $object -> getCTime() . microtime());
            }
        }

        $files = array_reverse(ksortBy($files, 'ts_make'));
        
        
        
        return $files;
    }

    /**
     * 
     * 
     */
    public function getSchedule() {
        $pathFile = $this -> Config -> get('path_data_schedule').$this -> Config -> get('schedule_week');
        $file = file($pathFile);
        $this -> Schedule = json_decode(implode($file), true);
        
        // calc percent start
        foreach($this -> Schedule as $dkey => $day){
            foreach($this -> Schedule[$dkey] as $ckey => $item ){
                $percent = (($item['m']/60) + $item['h'])/24*100;
                $this -> Schedule[$dkey][$ckey]['start_percent'] = intval($percent);
            }
        }
        
    }
    
    public function saveSchedule($data){
        if($data['show'] == '' || !$data['weekday'] || $data['hour']=='')
            return;
        
        
        $days = array_keys($data['weekday']);
        foreach($days as $day){
            
            // check if it exists on this day at this time
            $exists = false;
            foreach($this -> Schedule[$day] as $item) {
                if($item['show'] == $data['show'] && $item['h']==$data['hour'] && $item['m']==$data['minute'])
                    $exists = true;
            }
            
            if($exists==false){
                $this -> Schedule[$day][] = array(
                    'show' => $data['show'],
                    'h' => $data['hour'],
                    'm' => $data['minute'] 
                );
            }
        }
        
        $this->writeSchedule($show);
    }

    public function writeSchedule(){
        $pathFile = $this -> Config -> get('path_data_schedule').$this -> Config -> get('schedule_week');
        
        $fh = fopen($pathFile,'w+');
        fwrite($fh, json_encode($this->Schedule,true));
        fclose($fh);
    }
    
    public function writeNowPlayingShow($show){
        $pathFile = $this -> Config -> get('path_data_playlist').$this -> Config -> get('now_playing_show');
        $fh = fopen($pathFile,'w+');
        fwrite($fh, $show['slug']);
        fclose($fh);
        
        $pathFile = $this -> Config -> get('path_ramdisk').$this -> Config -> get('now_playing_show');
        $fh = fopen($pathFile,'w+');
        fwrite($fh, $show['slug']);
        fclose($fh);
        
        
        // save a stream meta message        
        $this -> StreamMeta -> set(
            array(
                'message'   => $show['name'].' . '.$show['meta'].' . '.$this -> Config -> get('station_name'),
                'start'     => time(), // timestamp, now
                'duration'  => $this -> Config -> get('stream_meta_duration'), // show for x seconds 
            ),
            $this
        );
        
    }
     
    public function getNowPlaylingShow(){
        $pathFile = $this -> Config -> get('path_ramdisk').$this -> Config -> get('now_playing_show');
        
        if(!file_exists($this -> Config -> get('path_ramdisk'))){
           return false; 
        }
        if(!file_exists($pathFile)){
            $pathAlternativeFile = $this -> Config -> get('path_data_playlist').$this -> Config -> get('now_playing_show');
            copy($pathAlternativeFile, $pathFile);
        }
        
        if(file_exists($pathFile)){
            $showName = trim(implode(file($pathFile)));
            
            foreach($this->Show as $s){
                if($s['slug']==$showName){
                    $now_playing_show = $s;
                }
            }
            
            return $now_playing_show;
        } else {
            
            
            return false;
        }
    }
   

    public function getNowPlayingSong(){
        $pathFile = $this -> Config -> get('path_ramdisk').$this -> Config -> get('now_playing_song');
        if(file_exists($pathFile)){
                
            $input = file($pathFile);    
            $splitRight = explode('(',$input[4]);
            $split2Right = explode(')',$splitRight[1]);         
            $seek = str_replace('%', '', $split2Right[0]);
            
            return array (
                'song' => $input[0].' - '.$input[1],
                'title' => $input[0],
                'artist' => $input[1],
                'seek' => $seek
            );
            
        } else {
            return false;
        }
    } 

    public function deleteSchedule($data){
        foreach($this -> Schedule[intval($data['wd'])] as $i=>$item) {
            if(trim($item['show']) == trim($data['s']) && intval($item['h'])===intval($data['h']) && intval($item['m'])===intval($data['m'])){  
                unset($this->Schedule[intval($data['wd'])][$i]);
            }
        }
        $this->writeSchedule();
    }
    
    public function cronSchedule(){
        $now = array(
            'wd'   => intval(date('N')),
            'h'    => intval(date('H')),
            'm'    => intval(date('i'))
        );
        
        print_r($now);
        
        //print_r($this -> Schedule);
        
        foreach($this -> Schedule[ $now['wd'] ] as $item){
            print_r($item);
            
            if(intval($item['h'])===$now['h'] && intval($item['m'])===$now['m']){
                $show = $this->getShowBy($item['show'],'slug');
                
                print_r($show);
                
                $this -> buildPlaylist( $show );
                return;
            }
        }
    }
    
    public function getShowBy($val, $key){
        $data = kfillBy($this->Show, $key);
        return $data[$val];
    }
}
?>