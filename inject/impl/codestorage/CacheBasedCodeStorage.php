<?php
namespace rush\inject\impl\codestorage;

use rush\cache\Cache;
use rush\inject\impl\CodeStorage;

class CacheBasedCodeStorage implements CodeStorage {
    private $ns,
            $cache;
    
    public function __construct($namespace, Cache $cache) {
        $this->ns = $namespace;
        $this->cache = $cache;
    }
    
    public function load($name) {
        $fqcn = $this->ns.'\\'.$name;
        if(class_exists($fqcn, false)) {
            return $fqcn;
        }
        
        $code = $this->cache->get($name);
        if($code) {
            eval($code);
            
            return $fqcn;
        }
    }
    
    public function store($name, $code) {
        $this->cache->set($name, 'namespace '.$this->ns.';

'.$code);
        
        return $this->ns.'\\'.$name;
    }
}