<?php  

class Xml {

    /*
     * XML Array
     * @var array
     * @access private
     */
    private $XMLArray;

    /*
     * array is OK
     * @var bool
     * @access private
     */
    private $arrayOK;

    /*
     * XML file name
     * @var string
     * @access private
     */
    private $XMLFile;

    /*
     * file is present
     * @var bool
     * @access private
     */
    private $fileOK;

    /*
     * DOM document instance
     * @var DomDocument
     * @access private
     */
    private $doc;

    /**
     * Constructor
     * @access public
     */

    public function __construct() {
        
    }

    /**
     * setteur setXMLFile
     * @access public
     * @param string $XMLFile
     * @return bool
     */

    public function setXMLFile($XMLFile) {
        if (file_exists($XMLFile)) {
            $this->XMLFile = $XMLFile;
            $this->fileOK = true;
        } else {
            $this->fileOK = false;
        }
        return $this->fileOK;
    }

    /**
     * saveArray
     * @access public
     * @param string $XMLFile
     * @return bool
     */

    public function saveArray($XMLArray = null, $rootName="response", $encoding="utf-8") {
        global $debug;

        if ($XMLArray) {
            $this->setArray($XMLArray);
        }

        $this->doc = new domdocument("1.0", $encoding);
        $arr = array();
        if (count($this->XMLArray) > 1) {
            if ($rootName != ""){
                $root = $this->doc->createElement($rootName);
            } else {
                $root = $this->doc->createElement("root");
                $rootName = "root";
            }
            $arr = $this->XMLArray;
        } else {

            $key = key($this->XMLArray);
            $val = $this->XMLArray[$key];

            if (!is_int($key)){
                $root = $this->doc->createElement($key);
                $rootName = $key;
            } else {
                if ($rootName != "") {
                    $root = $this->doc->createElement($rootName);
                } else {
                    $root = $this->doc->createElement("root");
                    $rootName = "root";
                }
            }
            $arr = $this->XMLArray[$key];
        }
        
        $root = $this->doc->appendchild($root);
    
        $this->addArray($arr, $root, $rootName);
        $this->doc->formatOutput = true;
        return $this->doc->saveXML();
    }

    /**
     * addArray recursive function
     * @access public
     * @param array $arr
     * @param DomNode &$n
     * @param string $name
     */

    function addArray($arr, &$n, $name="")
    {
        foreach ($arr as $key => $val) {
            if (is_int($key)) {
                if (strlen($name)>1) {
                    $newKey = substr($name, 0, strlen($name)-1);
                } else {
                    $newKey="item";
                }
            } else {
                $newKey = $key;
            }

            $node = $this->doc->createElement($newKey);
			if (is_array($val) && !count($val)) {
				$val = "";
			}
            if (is_array($val)) {
                $this->addArray($arr[$key], $node, $key);
            } else {
                $nodeText = $this->doc->createTextNode($this->_xml_convert($val));
                $node->appendChild($nodeText);
            }
            $n->appendChild($node);
        }
    }

    
    /**
     * setteur setArray
     * @access public
     * @param array $XMLArray
     * @return bool
     */

    public function setArray($XMLArray)
    {
        if (is_array($XMLArray) && count($XMLArray) != 0) {
            $this->XMLArray = $XMLArray;
            $this->arrayOK = true;
        } else {
            $this->arrayOK = false;
        }
        return $this->arrayOK;
    }

	protected function _xml_convert($str)
	{
		$temp = '__TEMP_AMPERSANDS__';

		$str = preg_replace("/&#(\d+);/", "$temp\\1;", $str);
		$str = preg_replace("/&(\w+);/",  "$temp\\1;", $str);
	
		$str = str_replace(array("&","<",">","\"", "'", "-"), array("&amp;", "&lt;", "&gt;", "&quot;", "&#39;", "&#45;"), $str);

		// Decode the temp markers back to entities		
		$str = preg_replace("/$temp(\d+);/","&#\\1;",$str);
		$str = preg_replace("/$temp(\w+);/","&\\1;", $str);
		
		return $str;
	}
}

?>