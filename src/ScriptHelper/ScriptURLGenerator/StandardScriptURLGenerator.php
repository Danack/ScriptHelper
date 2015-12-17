<?php


namespace ScriptHelper\ScriptURLGenerator;

use Blog\Route;
use ScriptHelper\CSSFile;
use ScriptHelper\ScriptURLGenerator;
use ScriptHelper\ScriptVersion;



class StandardScriptURLGenerator implements ScriptURLGenerator
{
    public function __construct(ScriptVersion $scriptVersion)
    {
        $this->scriptVersion = $scriptVersion;
    }

     public function arrayCSSFiles($cssList)
     {
         $separator = '';
         $fileList = '';

         foreach ($cssList as $cssFile) {
             /** @var $cssFile CSSFile */
             $fileList .= $separator;
             $fileList .= urlencode($cssFile->getFile());
             $separator = ',';
         }

         $url = sprintf(
             '/css/%s?%s',
             $fileList,
             $this->scriptVersion->getVersion()
         );

         return $url;
    }

    function arrayJSFiles($includeJSArray)
    {
        $commaSeparatedValues = '';
        $separator = '';
        foreach ($includeJSArray as $includeJS) {
            $commaSeparatedValues .= $separator;
            $commaSeparatedValues .= urlencode($includeJS);
            $separator = ',';
        }
        
        return Route::jsInclude($commaSeparatedValues).'?version='.$this->scriptVersion->getVersion();
    }
    
    /**
     * @var ScriptVersion
     */
    private $scriptVersion;

    public function singleCSSFile($cssName)
    {
        return sprintf( 
            '/css/%s.css?%s',
            $cssName,
            $this->scriptVersion->getVersion()
        );
    }

    public function singleJSFile($jsFilename)
    {
        return sprintf( 
            '/js/%s.js?version=%s',
            $jsFilename,
            $this->scriptVersion->getVersion()
        );
    }
}