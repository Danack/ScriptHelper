<?php


namespace ScriptHelper\FilePacker;

use ScriptHelper\FilePacker;
use ScriptHelper\ScriptHelperException;
use ScriptHelper\ScriptPath;


class PHPFilePacker implements FilePacker
{
    private $scriptPath;
    
    public function __construct(ScriptPath $scriptPath)
    {
        $this->scriptPath = $scriptPath;
    }

    public function getHeaders()
    {
        return [];
    }

    public function getFinalFilename(array $filesToPack, $extension)
    {
        return $this->scriptPath->getPath()."/testPack.js";
    }

    public function pack($jsIncludeArray, $appendLine, $extension)
    {
        $contents = '';
        foreach ($jsIncludeArray as $jsInclude) {
            $fileContents = @file_get_contents($jsInclude);
            if ($fileContents === false) {
                throw new ScriptHelperException("Could not read file $jsInclude");
            }
            $contents .= $appendLine;
            $contents .= $fileContents;
        }

        $outputFilename = $this->getFinalFilename($jsIncludeArray, $extension);
        file_put_contents($outputFilename, $contents);
    }
}
