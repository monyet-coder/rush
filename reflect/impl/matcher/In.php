<?php
namespace rush\reflect\impl\matcher;

class In extends StartsWith {
    public function __construct($namespace) {
        parent::__construct(trim($namespace, '\\').'\\');
    }
}