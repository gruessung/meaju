<?php
require_once('autoload.php');
require_once('includes/config.php');

use includes\system;


/**
 * Class API
 */
class redirect
{
    /**
     * @var system|null
     */
    private $oSys = null;

    /**
     * API constructor.
     */
    public function __construct()
    {
        $this->oSys = system::getInstance();
        $this->execute();
    }

    /**
     *
     */
    private function execute()
    {
        $sRequestURI =  $_SERVER["REQUEST_URI"];

        //Kurz URL finden
        $ex = explode('/', $sRequestURI);
        if (count($ex) == 3)
        {
            $sShort = $ex[1];
        }
        else
        {
            $sShort = preg_replace('%/%', '', $sRequestURI);
        }



        $oResult = $this->oSys->oDB->query('SELECT * FROM urls WHERE short = \''.str_replace('+', '', $sShort).'\'');
        $aResult = $oResult->fetch();
        if ($aResult == false) {
            header('Location: '.SITEURL);
            return;
        }

        if ($aResult['abuse'] == '1') {
            header('Location: '.SITEURL.'/abuse.html');
            return;
        }

        if ($sShort[strlen($sShort)-1] == '+') {
            $this->showStat($aResult);
            return;
        }

        $this->oSys->oDB->exec('INSERT INTO stat(`url`, `time`) VALUES ('.$aResult['id'].', '.time().')');

        header('Location: '.$aResult['long']);
        return;
    }

    private function showStat($aData) {
        $this->oSys->oTpl->addTemplate('design.tpl');
        $this->oSys->oTpl->appendTemplate('stat.tpl', array('url_short' => SITEURL.'/'.$aData['short'], 'url_long' => $aData['long']), 'CONTENT');

        $oResult = $this->oSys->oDB->query('SELECT * FROM stat WHERE url = '.$aData['id']);
        $aStat = array();
        while ($aRow = $oResult->fetch()) {
            if (!isset($aStat[date('d.m.Y', $aRow['time'])])) {
                $aStat[date('d.m.Y', $aRow['time'])] = 1;
            } else {
                $aStat[date('d.m.Y', $aRow['time'])]++;
            }
        }

        foreach($aStat as $sDatum => $iCnt) {
            $this->oSys->oTpl->setBlock('ZEILE', array('datum' => $sDatum, 'cnt' => $iCnt));
        }

        $this->oSys->oTpl->showOutput();
    }
}
new redirect();
?>

