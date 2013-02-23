<?php
namespace rush\inject;

/** @Annotation */
class Name implements Qualifier {
    public $value;
    
    static public function named($name) {
        $self = new static();
        
        $self->value = $name;
        
        return $self;
    }
    
    public function __toString() {
        $class = get_class($this);
        
        return '@'.$class.'('.
            implode(', ', array_map(function ($var) {
                return $var.'="'.$this->{$var}.'"';
            }, array_keys(get_class_vars($class)))).')';
    }
}