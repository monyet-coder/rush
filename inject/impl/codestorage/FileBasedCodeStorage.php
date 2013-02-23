<?php
namespace rush\inject\impl\codestorage;

use rush\inject\impl\CodeStorage;

class FileBasedCodeStorage implements CodeStorage {
    private $ns,
            $path;
    
    public function __construct($namespace, $path) {
        $this->ns = $namespace;
        $this->path = $path;
    }
    
    public function load($name) {
        $fqcn = $this->ns.'\\'.$name;
        if(class_exists($fqcn, false)) {
            return $fqcn;
        }
        
        $path = $this->path.'/'.str_replace('\\', '/', $fqcn).'.php';
        if(is_file($path)) {
            require $path;

            return $fqcn;
        }
        
        return false;
    }
    
    public function store($name, $code) {
        $fqcn = $this->ns.'\\'.$name;
        $path = $this->path.'/'.str_replace('\\', '/', $fqcn).'.php';
        
        file_put_contents($path, '<?php
namespace '.$this->ns.';

'.$code, LOCK_EX);
    }
}