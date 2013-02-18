<?php

// these are from http://davidhancock.co/2012/11/useful-php-functions-for-dealing-with-the-file-system/

function delete_recursive($path)
{
    if (is_dir($path))
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file)
        {
            if ($file->isDir())
            {
                rmdir($file->getPathname());
            }
            else
            {
                unlink($file->getPathname());
            }
        }

        //rmdir($path);
    }
    else
    {
        unlink($path);
    }
}

function copy_recursive($source, $dest)
{
    if (is_dir($source))
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file)
        {
            if ($file->isDir())
            {
                mkdir($dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
            }
            else
            {
                copy($file, $dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
            }
        }
    }
    else
    {
        copy($source, $dest);
    }
}

// from http://php.net/manual/en/domdocument.createcdatasection.php
class SimpleXMLExtended extends SimpleXMLElement{ 
    public function addCData($string){ 
        $dom = dom_import_simplexml($this);
        $cdata = $dom->ownerDocument->createCDATASection($string);
        $dom->appendChild($cdata);
    } 
}

?>