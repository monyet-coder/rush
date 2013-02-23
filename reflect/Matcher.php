<?php
namespace rush\reflect;

interface Matcher {
    function matches(Type $type);
}