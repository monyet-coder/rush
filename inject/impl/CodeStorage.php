<?php
namespace rush\inject\impl;

interface CodeStorage {
    function load($name);
    
    function store($name, $code);
}