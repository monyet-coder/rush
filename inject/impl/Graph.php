<?php
namespace rush\inject\impl;

use IteratorAggregate;
use rush\inject\Key;

interface Graph extends IteratorAggregate {
    function addNode(Key $key, GraphNode $node);
    
    function getNode(Key $key);
}