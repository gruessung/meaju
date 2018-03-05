<?php
function meaju_autoload($class)
{
    $parts = explode('\\', $class);
    $sFile = implode(DIRECTORY_SEPARATOR, $parts) . '.php';
    if (file_exists($sFile)) {
        require_once $sFile;
    } else {
        return false;
    }
}
spl_autoload_register('meaju_autoload');
?>