<?php
namespace rush\reflect\impl;

use rush\reflect\Loader;

class LoaderImpl implements Loader {
    public $path, $prefix, $stat, $level;
    
    public function __construct($prefix, $path, $stat = false) {
        $this->path = $path;
        $this->prefix = $prefix;
        $this->stat = $stat;
        $this->level = substr_count($prefix, '\\');
    }
    
    public function __invoke($className) {
        if(strpos($className, $this->prefix) !== 0) {
            return false;
        }
        
        $filepath = $this->path.'/'.str_replace('\\', '/', $className).'.php';
        if($this->stat && !is_file($filepath)) {
            return false;
        }
        
        require $filepath;
        
        return true;
    }

    public function getPath() {
        return $this->path;
    }

    public function getPrefix() {
        return $this->prefix;
    }
    
    public function getLevel() {
        return $this->level;
    }
}