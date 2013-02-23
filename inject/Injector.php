<?php
namespace rush\inject;

interface Injector {
    function getInstance($typeName);
    
    function getInstanceByKey(Key $key);
    
    function getLazy(TypeKey $key);
}