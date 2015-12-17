<?php

namespace ScriptServer\Controller;

use Tier\Path\WebRootPath;
use ScriptServer\FilePacker;
use Room11\HTTP\Request;
use Room11\HTTP\Response;
use Tier\ResponseBody\CachingGeneratingFileResponseFactory;

function extractItems($cssInclude)
{
    $items = [];
    $cssIncludeArray = explode(',', $cssInclude);
    foreach ($cssIncludeArray as $cssIncludeItem) {
        $cssIncludeItem = urldecode($cssIncludeItem);
        $cssIncludeItem = trim($cssIncludeItem);
        $versionString = str_replace(
            array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "."),
            "",
            $cssIncludeItem
        );
        
        $versionString = trim($versionString);

        if (mb_strlen($versionString) == 0) {
            //This isn't actually a JS include but is instead a version number
            continue;
        }

        $items[] = $cssIncludeItem;
    }

    return $items;
}

class ScriptServer
{
    /**
     * @var FilePacker
     */
    private $filePacker;

    /** @var CachingGeneratingFileResponseFactory  */
    private $fileResponseFactory;
    
    public function __construct(
        Response $response,
        CachingGeneratingFileResponseFactory $fileResponseFactory,
        FilePacker $filePacker,
        WebRootPath $webRootPath
    ) {
        $this->fileResponseFactory = $fileResponseFactory;
        $this->webRootPath = $webRootPath->getPath();
        $this->filePacker = $filePacker;
        $this->response = $response;
    }

    /**
     * @param $cssInclude
     * @return array
     */
    private function getCSSFilesToInclude($cssInclude)
    {
        $files = array();
        $items = extractItems($cssInclude);
        foreach ($items as $item) {
            $files[] = $this->getCSSFilename($item);
        }

        return $files;
    }

    /**
     * @param $cssIncludeItem
     * @return string
     */
    private function getCSSFilename($cssIncludeItem)
    {
        $cssIncludeItem = str_replace(array("\\", ".." ), "", $cssIncludeItem);

        return $this->webRootPath."/css/".$cssIncludeItem.".css";
    }

    /**
     * @param $jsIncludeItem
     * @return string
     */
    private function getJavascriptFilename($jsIncludeItem)
    {
        $jsIncludeItem = str_replace(array("\\", ".."), "", $jsIncludeItem);

        return $this->webRootPath . "js/" . $jsIncludeItem . ".js";
    }

    /**
     * @param $jsInclude
     * @return array
     */
    private function getJSFilesToInclude($jsInclude)
    {
        $files = array();
        $items = extractItems($jsInclude);
        foreach ($items as $item) {
            $files[] = $this->getJavascriptFilename($item);
        }

        return $files;
    }

    /**
     * @param Request $request
     * @param $cssInclude
     * @return \Room11\HTTP\Body
     */
    public function serveCSS($commaSeparatedFilenames)
    {
        $cssIncludeArray = $this->getCSSFilesToInclude($commaSeparatedFilenames);

        return $this->getPackedFiles(
            $cssIncludeArray,
            $appendLine = "\n",
            'text/css',
            'css'
        );
    }


    /**
     * @param Request $request
     * @param $jsInclude
     * @return \Room11\HTTP\Body
     */
    public function serveJavascript($commaSeparatedFilenames)
    {
        $jsIncludeArray = $this->getJSFilesToInclude($commaSeparatedFilenames);

        return $this->getPackedFiles(
            $jsIncludeArray,
            $appendLine = "",
            'application/javascript',
            'js'
        );
    }

    /**
     * @param $jsIncludeArray
     * @param $appendLine
     * @param $contentType
     * @param $extension
     * @return \Room11\HTTP\Body
     */
    private function getPackedFiles($jsIncludeArray, $appendLine, $contentType, $extension)
    {
        $finalFilename = $this->filePacker->getFinalFilename($jsIncludeArray, $extension);
        $fn = function () use ($jsIncludeArray, $appendLine, $extension) {
            $this->filePacker->pack($jsIncludeArray, $appendLine, $extension);
        };

        return $this->fileResponseFactory->create(
            $finalFilename,
            $contentType,
            $fn,
            $this->filePacker->getHeaders()
        );
    }
}