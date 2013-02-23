<?php
namespace rush\reflect;

interface InvocationHandler {
    function invoke(Method $method, array $args);
}