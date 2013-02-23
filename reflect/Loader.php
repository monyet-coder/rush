<?php
namespace rush\reflect;

interface Loader {
    function __invoke($className);
    
    function getPath();
    
    function getPrefix();
}