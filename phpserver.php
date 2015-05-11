<?php

/**
 * PHP Server router script
 */

// determine the file we're loading, we need to strip the query string for that
if (isset($_SERVER['SCRIPT_NAME']))
{
    $file = $_SERVER['DOCUMENT_ROOT'].$_SERVER['SCRIPT_NAME'];
}
else
{
    $file = $_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'];
    if (($pos = strpos($file, '?')) !== false)
    {
        $file = substr($file, 0, $pos);
    }
}

if (file_exists($file))
{
    // bypass existing file processing
    return false;
}
else
{
    // route requests though the normal path
    include($_SERVER['SCRIPT_NAME'] = $_SERVER['DOCUMENT_ROOT'].'/index.php');
}
