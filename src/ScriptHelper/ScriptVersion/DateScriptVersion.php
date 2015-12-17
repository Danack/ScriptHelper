<?php

namespace ScriptServer\ScriptVersion;

use ScriptServer\ScriptVersion;

class DateScriptVersion implements ScriptVersion
{
    private $version;

    public function __construct()
    {
        $this->version = date('Y_m_d');
    }

    public function getVersion()
    {
        return $this->version;
    }
}