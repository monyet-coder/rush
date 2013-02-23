<?php
namespace rush\aspect\intercept;

interface MethodInvocation extends Invocation {
    /**
     * @return \rush\reflect\Method
     */
    public function getMethod ();
}