<?php
require_once('autoload.php');
require_once('includes/config.php');

use includes\system;


/**
 * Class API
 */
class API
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
        $sLong = $_POST['url'];

        if (strpos($sLong, 'http') === false) {
            $sLong = 'http://'.$sLong;
        }

        if(preg_match( '/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' ,$sLong) == false){
            $this->returnData(403, L::wrong_format);
        }

        $bDone = false;
        while (!$bDone) {
            $sShort = $this->generateRandomString(URL_LENGTH);

            $oResult = $this->oSys->oDB->query('SELECT COUNT(*) as cnt FROM urls WHERE short = \''.$sShort.'\'');
            $aResult = $oResult->fetch();
            if ($aResult['cnt'] == 0) {
                $bDone = true;
                $oStmt = $this->oSys->oDB->prepare('INSERT INTO urls (`short`, `long`, `userid`) VALUES (?,?,0)');
                if($oStmt->execute(array($sShort, $sLong))) {
                    $this->returnData(200, 'ok',$sLong, L::siteurl.'/'.$sShort);
                }
            }
        }
    }

    /**
     * @param        $iStatus
     * @param        $sMessage
     * @param string $sLong
     * @param string $sShort
     */
    private function returnData($iStatus, $sMessage, $sLong = '', $sShort = '') {
        $aData = array();
        $aData['version'] = '0.1';
        $aData['status'] = $iStatus;
        $aData['message'] = $sMessage;
        $aData['long'] = $sLong;
        $aData['short'] = $sShort;
        echo json_encode($aData);
        die();
    }


    /**
     * @param int $length
     * @return string
     */
    private function generateRandomString($length = 10)
    {
        $characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

new API();



