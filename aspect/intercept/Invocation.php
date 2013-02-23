<?php
namespace rush\aspect\intercept;

use rush\aspect\JoinPoint;

interface Invocation extends JoinPoint {
    public function getArguments ();
}