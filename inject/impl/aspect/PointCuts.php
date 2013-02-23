<?php
namespace rush\inject\impl\aspect;

use rush\reflect\Method;
use rush\inject\impl\GraphNode;

class PointCuts {
    private $advices = [], 
            $pointCuts = [];
    
    public function addPointCut(Method $method, GraphNode $node) {
        $this->advices[] = $node;
        $this->pointCuts[] = new PointCut($method->getAnnotations('rush\inject\Intercept'));
    }
    
    public function getAdvicesFor(Method $method) {
        $advices = [];
        foreach($this->pointCuts as $i => $pointCut) {
            if($pointCut->matches($method)) {
                $advices[] = $this->advices[$i];
            }
        }
        
        return $advices;
    }
}