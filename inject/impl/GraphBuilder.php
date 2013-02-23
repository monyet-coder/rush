<?php
namespace rush\inject\impl;

class GraphBuilder {
    private $modules,
            $moduleBinder;
    
    public function __construct(
            array $modules,
            ModuleBinder $moduleBinder) {
        $this->modules = $modules;
        $this->moduleBinder = $moduleBinder;
    }
    
    public function build() {
        foreach($this->modules as $module) {
            $this->moduleBinder->bindModuleNamed($module);
        }
    }
}