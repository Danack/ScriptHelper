<?php

use Auryn\Injector;

use ScriptHelper\ScriptPath;

$autoloader = require(__DIR__.'/../vendor/autoload.php');

//$autoloader->add('JigTest', [realpath('./').'/test/']);


/**
 * @return \Auryn\Injector
 * @throws \Auryn\ConfigException
 */
function createTestInjector($mocks = array(), $shares = array())
{
    $injector = new \Auryn\Injector();

    // Read application config params
    $injectionParams = require __DIR__."/./testInjectionParams.php";
    /** @var $injectionParams \Tier\InjectionParams */
    $injector->share(new ScriptPath(__DIR__."/../fixtures/"));

    //$injectionParams->mergeMocks($mocks);
    $injectionParams->addToInjector($injector);
    foreach ($mocks as $class => $implementation) {
        if (is_object($implementation) == true) {
            $injector->alias($class, get_class($implementation));
            $injector->share($implementation);
        }
        else {
            $injector->alias($class, $implementation);
        }
    }
    
    
    $injector->share($injector);
    
    return $injector;
}