<?php
namespace rush;

use rush\reflect\impl\ReflectorImpl;
use rush\inject\impl\InjectorImpl;
use rush\inject\Provides;
use rush\inject\Singleton;
use rush\inject\impl\codestorage\FileBasedCodeStorage;
use rush\inject\impl\codestorage\CacheBasedCodeStorage;
use rush\cache\Cache;
use rush\cache\storage\DummyStorage;

require __DIR__.'/reflect/Loader.php';
require __DIR__.'/reflect/Reflector.php';
require __DIR__.'/reflect/impl/LoaderHeap.php';
require __DIR__.'/reflect/impl/Autoloader.php';
require __DIR__.'/reflect/impl/LoaderImpl.php';
require __DIR__.'/reflect/impl/ReflectorImpl.php';

final class Rush {
    private function __construct() {}
    
    /**
     * @return reflect\Reflector
     */
    static public function getReflector() {
        static $reflector;
        
        return $reflector ?: $reflector = ReflectorImpl::create();
    }
    
    static public function getRequest() {
        
    }
    
    /**
     * @return \rush\inject\Injector
     */
    static public function createInjector(array $modules = []) {
        $reflector = self::getReflector();
        
        return InjectorImpl::create(
            $modules,
            $reflector,
            new FileBasedCodeStorage('gen\inject', __DIR__.'/../..')
        );
    }
    
    /**
     * @return \rush\inject\Injector
     */
    static public function createDebugInjector(array $modules = []) {
        $reflector = self::getReflector();
        
        $cache = new Cache('rush$inject', new DummyStorage());
        
        return InjectorImpl::create(
            $modules,
            $reflector,
            new CacheBasedCodeStorage('gen\inject', $cache)
        );
    }
}