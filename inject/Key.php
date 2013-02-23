<?php
namespace rush\inject;

use rush\reflect\Parameter;

abstract class Key {
    private $qualifier;
    
    protected function __construct(Qualifier $qualifier = null) {
        $this->qualifier = $qualifier;
    }
    
    public function getQualifier() {
        return $this->qualifier;
    }
    
    public function __toString() {
        return $this->getQualifier() ?: '';
    }
    
    public function hash() {
        return md5(serialize($this));
    }
    
    static public function ofType($type, Qualifier $qualifier = null) {
        return new TypeKey($type, $qualifier);
    }
    
    static public function ofConstant(Qualifier $qualifier) {
        return new ConstantKey($qualifier);
    }
    
    static public function ofElement(Qualifier $qualifier) {
        return new ElementKey($qualifier);
    }
    
    static public function ofParameter(Parameter $param) {
        if(($class = $param->getClass())) {
            return self::ofType($class->name, $param->getAnnotation('rush\inject\Qualifier'));
        }
        
        if($param->isArray()) {
            return self::ofElement($param->getAnnotation('rush\inject\Qualifier'));
        }
        
        return self::ofConstant($param->getAnnotation('rush\inject\Qualifier'));
    }
}

class TypeKey extends Key {
    private $type;
    
    protected function __construct($type, Qualifier $qualifier = null) {
        parent::__construct($qualifier);
        
        $this->type = $type;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function __toString() {
        return $this->getType().parent::__toString();
    }
}

class ConstantKey extends Key {
    public function __toString() {
        return 'const'.parent::__toString();
    }
}

class ElementKey extends Key {
    public function __toString() {
        return 'array'.parent::__toString();
    }
}

