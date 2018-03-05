<?php
require_once('autoload.php');
require_once('includes/config.php');
use includes\system;

$oSys = system::getInstance();



//append index.tpl
$oSys->oTpl->appendTemplate('design.tpl');

//check $_GET['p']
if (isset($_GET['p'])) {
    $sPage = $_GET['p'];
} else {
    $sPage = 'index';
}

if (file_exists('pages/'.$sPage.'.php')) {
    require_once('pages/'.$sPage.'.php');
} else if (file_exists('layout/'.$sPage.'.tpl')) {
    $oSys->oTpl->appendTemplate($sPage.'.tpl', array(), 'CONTENT');
} else {
    die('404');
}


$oSys->oTpl->showOutput();
?>