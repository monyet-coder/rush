<?php
namespace rush\inject;

/** @Annotation */
final class Provides {
    const CONSTANT = 'const';
    const ELEMENT = 'array';
    
    public $value = self::CONSTANT;
}