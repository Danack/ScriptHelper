<?php

use Tier\InjectionParams;

use ScriptHelper\ScriptPath;

// These classes will only be created  by the injector once
$shares = [
    new ScriptPath(__DIR__."/fixtures/")
];
    

// Alias interfaces (or classes) to the actual types that should be used 
// where they are required. 
$aliases = [
    'Room11\Caching\LastModifiedStrategy' => 'Room11\Caching\LastModified\Disabled',
    'ScriptHelper\FilePacker' => 'ScriptHelper\FilePacker\PHPFilePacker',
];


// Delegate the creation of types to callables.
$delegates = [
    //\GithubService\GithubArtaxService\GithubService::class => 'createGithubService',
    //\Doctrine\DBAL\Query\QueryBuilder::class => 'createDBALQueryBuilder'
];

// If necessary, define some params that can be injected purely by name.
$params = [ ];

$defines = [
];

$prepares = [

];

$injectionParams = new InjectionParams(
    $shares,
    $aliases,
    $delegates,
    $params,
    $prepares,
    $defines
);

return $injectionParams;
