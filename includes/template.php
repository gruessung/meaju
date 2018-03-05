<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 10.12.2015
 * Time: 10:56
 */

namespace includes;

class template
{
    public static $oInstance = null;
    private $sPath           = './layout/';
    private $sOutput         = '';
    private $aBlocks         = array();

    public function __construct()
    {

    }

    public static function getInstance()
    {
        if (self::$oInstance instanceof template) {
            return self::$oInstance;
        }
        return new template();
    }

    /**
    Fügt ein Template im Ganzen ein, sinnlos wenn man Tabellen hat oder sich wiederholende Inhalt
     */
    public function addTemplate($sTemplate, $sKnoten = '')
    {
        if (file_exists($this->sPath . $sTemplate)) {
            $sFile = file_get_contents($this->sPath . $sTemplate);
            $sFile = $this->searchBlocks($sFile);
            if (empty($sKnoten)) {
                $this->sOutput = $sFile;
            } else {
                $this->sOutput = str_replace('{' . $sKnoten . '}', $sFile, $this->sOutput);
            }
        } else {
            echo 'Template <b>' . $this->sPath . $sTemplate . '</b> nicht gefunden';
        }

    }

    public function appendTemplate($sTemplate, $aValues = array(), $sKnoten = '')
    {
        if (file_exists($this->sPath . $sTemplate)) {
            $sFile = file_get_contents($this->sPath . $sTemplate);
            $sFile = $this->searchBlocks($sFile);
            foreach ($aValues as $k => $v) {
                $sFile = str_replace('{' . $k . '}', $v, $sFile);
            }

            if (empty($sKnoten)) {
                $this->sOutput .= $sFile;
            } else {
                $this->set($sKnoten, $sFile);
            }
        } else {
            echo 'Template <b>' . $this->sPath . $sTemplate . '</b> nicht gefunden.<br />';
        }
    }

    public function set($sKnoten, $sInhalt)
    {
        $this->sOutput = str_replace('{' . $sKnoten . '}', $sInhalt, $this->sOutput);
    }

    public function showOutput()
    {
        //Get all language variables
        $aTplData = array();
        $oReflectionClass = new \ReflectionClass(new \L());
        foreach($oReflectionClass->getConstants() as $k => $v) {
            $this->set('lng_'.$k, $v);
        }


        //Ungenutze Variablen werden leer gemacht
        preg_match_all("/\{(.*)\}/iU", $this->sOutput, $matches);
        foreach ($matches[1] as $e) {
            $this->set($e, '');
        }
        //temporäre Blockvariablen werden hier gelöscht
        preg_match_all("/\{!(.*)\}/iU", $this->sOutput, $matches);
        foreach ($matches[1] as $e) {
            $this->set($e, '');
        }
        echo $this->sOutput;
    }

    /**
     * Im Tpl gesetzt Blöcke werden hier aufgerufen
     * @param type $sKnoten
     * @param type $aValues
     * @return type
     */
    public function setBlock($sKnoten, $aValues = array())
    {
        if (isset($this->aBlocks[$sKnoten])) {
            $sTpl = $this->aBlocks[$sKnoten]['html'];
            foreach ($aValues as $k => $v) {
                $sTpl = str_replace('{' . $k . '}', $v, $sTpl);
            }
            $sTpl .= '{!' . $sKnoten . '}';
            $this->sOutput = str_replace('{!' . $sKnoten . '}', $sTpl, $this->sOutput);
        } else {
            echo 'Block <b>' . $sKnoten . '</b> nicht gefunden.<br />';
        }
    }

    /**
     * Sucht nach TemplateBlöcken
     *
     * Im Template kann ein Block gesetzt werden:
     * <{BLOCK=NAME}>
     * Inhalt
     * <{/BLOCK=NAME}>
     *
     * In diesem Block kann kein weiterer Block sein!
     *
     * @param type $sTemplate
     * @return type
     */
    private function searchBlocks($sTemplate)
    {
        $re = '/<\{BLOCK=(.*)\}>(.*)<\{\/BLOCK=(\1)\}>/siU';
        preg_match_all($re, $sTemplate, $matches);
        if (count($matches[0]) > 0) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                if (isset($this->aBlocks[$matches[1][$i]])) {
                    echo "Block <b>" . $matches[1][$i] . "</b> bereits vorhanden.<br />";
                } else {
                    $this->aBlocks[$matches[1][$i]] = array(
                        'tag'    => $matches[1][$i],
                        'html'   => $matches[2][$i],
                        'gesamt' => $matches[0][$i],
                    );
                    $sTemplate = preg_replace('/<\{BLOCK=' . $matches[1][$i] . '\}>(.*)<\{\/BLOCK=(' . $matches[1][$i] . ')\}>/siU', '{!' . $matches[1][$i] . '}', $sTemplate);

                }
            }
        }
        return $sTemplate;
    }

}