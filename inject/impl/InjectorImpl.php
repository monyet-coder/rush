<?php
namespace rush\inject\impl;

use rush\inject\Key;
use rush\inject\TypeKey;
use rush\inject\Injector;
use rush\reflect\Reflector;
use rush\inject\impl\node\InstanceNode;
use rush\inject\impl\FactoryFactory;
use rush\inject\impl\aspect\PointCuts;

class InjectorImpl implements Injector {    
    private $graph,
            $singletons,
            $factory,
            $lazy;
    
    public function __construct(
            Graph $graph,
            SingletonPool $singletons,
            FactoryFactory $factory,
            LazyProxyFactory $lazy) {
        $this->graph = $graph;
        $this->singletons = $singletons;
        $this->factory = $factory;
        $this->lazy = $lazy;
    }
    
    public function getInstance($typeName) {
        return $this->getInstanceByKey(Key::ofType($typeName));
    }

    public function getInstanceByKey(Key $key) {
        $factory = $this->factory->getFactoryOf($key);
        
        return $factory::create($this->singletons, $this);
    }

    public function getLazy(TypeKey $key) {
        $fqcn = $this->lazy->getProxyClass($key);
                
        return new $fqcn($this, $key);
    }
    
    public function bindInstance(Key $key, $instance) {
        $index = $this->singletons->pushInstance($instance);
        
        $this->graph->addNode($key, new InstanceNode($index));
    }
    
    static public function create(
            array $modules,
            Reflector $reflector, 
            CodeStorage $codeStorage) {
        $singletons = new SingletonPool();
        $delegateGraph = new GraphImpl();
        $typeBinder = new TypeBinder($delegateGraph, $reflector);
        $moduleBinder = new ModuleBinder($delegateGraph, $reflector);
        $builder = new GraphBuilder($modules, $moduleBinder, $typeBinder);
        $resolverVisitor = new ModuleResolverVisitor($delegateGraph, $typeBinder, $singletons);
        $graph = new LazyGraph($builder, $delegateGraph, $resolverVisitor);
        
        $factory = new FactoryFactory($graph, $typeBinder, $codeStorage);
        $lazyGen = new LazyProxyGenerator();
        $lazy = new LazyProxyFactory($codeStorage, $reflector, $lazyGen);
        $injector = new self($graph, $singletons, $factory, $lazy);
        
        $injector->bindInstance(Key::ofType('rush\inject\Injector'), $injector);
        $injector->bindInstance(Key::ofType('rush\inject\Reflector'), $reflector);
        
        return $injector;
    }
}