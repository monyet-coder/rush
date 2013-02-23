<?php
namespace rush\inject\impl;

interface GraphNode {
    function accept(GraphNodeVisitor $visitor);
}