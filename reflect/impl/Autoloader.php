<?php
namespace rush\reflect\impl;

class Autoloader {    
    public $loaders = array();
    
    public function autoload($prefix, $path) {        
        $loaders = new LoaderHeap();
        $loader = new LoaderImpl($prefix, $path);
        
        foreach($this->loaders as $l) {
            $loaders->insert($l);
        }
        $loaders->insert($loader);
        
        $this->loaders = array();
        foreach($loaders as $l) {
            $this->loaders[] = $l;
        }
        
        spl_autoload_register($loader);
        
        return $loader;
    }
    
    public function getLoaders() {
        return $this->loaders;
    }
}