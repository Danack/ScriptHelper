<?php

use ScriptHelperTest\BaseTestCase;
use \Room11\HTTP\RequestHeaders\ArrayRequestHeaders;

class ScriptServerTest extends BaseTestCase
{
    function testPackedFiles()
    {
        $headers = new ArrayRequestHeaders([]);
        $injector = createTestInjector(
            ['Room11\HTTP\RequestHeaders' => $headers]
        );

        $scriptServer = $injector->make('ScriptHelper\Controller\ScriptServer');
        $scriptServer->serveJavascript("file1,file2");
    }
}
