<?php
namespace rush\inject\impl;

class Compiler {
    private $code,
            $indent = 0;
    
    public function write($code) {
        $this->code .= $code;
    }
    
    public function writeln($code) {
        $this->write(str_repeat('    ', $this->indent));
        $this->write($code);
        $this->nl();
    }
    
    public function nl() {
        $this->write("\n");
        $this->write(str_repeat('    ', $this->indent));
    }
    
    public function indent() {
        ++$this->indent;
    }
    
    public function outdent() {
        if($this->indent > 0) {
            --$this->indent;
        }
    }
    
    public function writeScalar($value) {
        if(is_array($value)) {
            $this->write('[');
            foreach($value as $k => $v) {
                $this->writeScalar($k);
                $this->write(' => ');
                $this->writeScalar($v);
                $this->write(', ');
            }
            $this->write(']');
        } else if(is_string($value)) {
            $this->write("'{$value}'");
        } else if(is_numeric($value)) {
            $this->write($value);
        } else if(is_null($value)) {
            $this->write('null');
        }
    }
    
    public function __toString() {
        return $this->code;
    }
}