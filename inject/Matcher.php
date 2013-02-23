<?php
namespace rush\inject;

use rush\reflect\Method;

interface Matcher {
    function matches(Method $method);
}