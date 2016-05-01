<?php

namespace ScriptHelper;

use ScriptHelper\ScriptHelperException;

class ScriptPath
{
    private $path;

    public function __construct($path)
    {
        if ($path === null) {
            throw new ScriptHelperException("Path cannot be null for ScriptPath.");
        }
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }
}
