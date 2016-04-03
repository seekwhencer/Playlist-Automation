<?php

class Podcast {

    public $Config;
    public $Feed;
    
    public $Suffix = array('.mp3');
    public $Excludes = array('Thumbs.db', '.', '..');

    public function __construct() {
        include_once('source/lib/Config.php');
        $this -> Config = new Config();
        $this -> getPodcasts();
    }

    public function getConfig() {
        $filePath = PATH_DATA . "" . STATION_CONFIG;
        if (file_exists($filePath)) {
            $this -> Config = json_decode(implode(file($filePath)), true);
        }
    }

    public function getPodcasts() {

        $path = $this -> Config -> get('path_data_podcast');
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
            $filePath = $path . $fileName;
            $file = file($filePath);

            if (trim($file[0]) != '') {
                $data = json_decode(implode($file), true);
                $data['file_name'] = str_replace('.json', '', $fileName);
                $this -> Feed[$data['file_name']] = $data;
            }
        }

        
        $this -> Feed = ksortBy($this -> Feed, 'timestamp');
        $this -> Feed = kfillBy($this -> Feed, 'file_name');

        $this -> Feed = array_reverse($this -> Feed);

    }
    
    public function getFolders($recursive = false) {

        $path = $this -> Config -> get('path_podcast');
        $files = array();

        $folders = array();
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
    
    public function getPodcast($fileName) {

        if ($this -> Feed[$fileName])
            return $this -> Feed[$fileName];

        return false;

    }

}
?>
