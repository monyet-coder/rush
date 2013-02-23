<?php
namespace rush\aspect\intercept;

interface MethodInterceptor extends Interceptor {
    public function intercept (MethodInvocation $invocation);
}