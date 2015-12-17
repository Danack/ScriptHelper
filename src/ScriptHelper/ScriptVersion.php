<?php

namespace ScriptServer;

interface ScriptVersion
{
    /**
     * @return string A const string that represents the version number for all
     * the scripts on a site.
     */
    public function getVersion();
}

