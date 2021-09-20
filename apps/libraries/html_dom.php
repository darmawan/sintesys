<?php
/*******************************************************************************
Version: 0.2
Date : 2011-06-19
Website: http://www.ecliptik.net/html-dom.html
Website doc: http://www.ecliptik.net/html-dom.html
Author: alex michaud <alex.michaud@gmail.com>
Licensed under The MIT License
Redistribution of file must retain the above copyright notice.
*******************************************************************************/

/**
 * Load a html_dom object from a html string
 * @param string $str
 * @return html_dom object
 */
function str_get_html($str)
{
	$html_dom = new html_dom;
	$html_dom->loadHTML($str);
	return $html_dom;
}

/**
 * Load a html_dom object from a html file
 * @param $file_path
 * @return html_dom object
 */
function file_get_html($file_path)
{
	$html_dom = new html_dom;
	$html_dom->loadHTMLFile($file_path);
	return $html_dom;
}


class html_dom 
{
	public $dom;
	public $lowercase = false;
	
	public function __construct($dom = null)
	{
		if(!is_null($dom))
			$this->dom = $dom;
	}
	
	/**
	 * Convert a CSS selector (similar to jQuery) to a valid xpath selector
	 * @param string $q
	 * @return 
	 */
	public static function cssSelectorToXPath($q)
	{
		$patterns = array();
		$patterns[0] = '/^([a-z\-:_\.]+)/';
		$patterns[1] = '/^#/';
		$patterns[2] = '/^\./';
		$patterns[3] = '/\s+/';
		$patterns[4] = '/(#)([\w\-:_\.]+)/';
		$patterns[5] = '/(\.)([\w\-:_\.]+)/';
		$replacements = array();
		$replacements[0] = '\1';
		$replacements[1] = '*#';
		$replacements[2] = '*.';
		$replacements[3] = '/';
		$replacements[4] = '[@id="\2"]';
		$replacements[5] = '[contains(@class,"\2")]';
		//$replacements[5] = '[@class and contains(concat(" ",normalize-space(@class)," "),"\2")]';
		$a = preg_replace($patterns, $replacements, $q);
		return $a;
	}
	
	/**
	 * Load a html_dom object from a html string
	 * @param string $str
	 */
	public function loadHTML($str, $encoding = "UTF-8")
	{
		libxml_use_internal_errors(true);
		$this->dom = new DOMDocument('1.0', $encoding);
		$this->dom->formatOutput = true;
		
		$str = mb_convert_encoding($str, "HTML-ENTITIES", $encoding);// need this to fix encoding problem
		$this->dom->loadHTML('<?xml encoding="'.$encoding.'">' .$str);
		foreach ($this->dom->childNodes as $item)
	    	if ($item->nodeType == XML_PI_NODE)
	        	$this->dom->removeChild($item);
		
		$this->dom->encoding = $encoding;
	}
	
	/**
	 * Load a html_dom object from a html file
	 * @param $file_path
	 */
	public function loadHTMLFile($file_path)
	{
		$this->loadHTML(file_get_contents($file_path));
	}
	
	/**
	 * Output HTML file to the screen, and save it to a file if a file path is specified
	 * @param $file_path [optional]
	 * @return HTML file
	 */
	public function save($file_path = "")
	{
		if(!empty($file_path))
			$this->dom->saveHTMLFile($file_path);
		
		return $this->dom->saveHTML();
	}
	
	/**
	 * Find 1 or more dom element matching the css selector
	 * @param string $selector
	 * @param int $index [optional]
	 * @return 1 dom element if index is specified or array of dom element if index is null
	 */
	public function find($selector, $index = null)
	{
		$aElements = array();
		
		$dom_xpath = new html_dom_xpath($this->dom);
		
		$xpathSelector = html_dom::cssSelectorToXPath($selector);
		$aElements = $dom_xpath->select($xpathSelector);
		
		if($index<0) 
			$index = count($aElements) + $index;
		
		if(is_null($index))
			return $aElements;
		else
			return (isset($aElements[$index]))?$aElements[$index]:array();
	}
}

class html_dom_xpath
{
	private $xpath;
	
	function __construct(&$dom)
	{
		$this->dom = $dom;
		$this->xpath = new DOMXpath($this->dom);
		$this->xpath->registerNamespace('html','http://www.w3.org/1999/xhtml');
	}
	
	/**
	 * Perform a xpath query
	 * @param string $q
	 * @param $relatedNode [optional]
	 * @return array of html dom element
	 */
	public function select($q, &$relatedNode = null)
	{
		if(is_null($relatedNode))
		{
			$nodeList = $this->xpath->query("//".$q);
			$isRelated = "no";
		}
		else
		{
			$nodeList = $this->xpath->query("./".$q, $relatedNode);
			$isRelated = "yes";
		}
		
		$a = array();
		if($nodeList !== false)
		{
			foreach($nodeList as $node)
				$a[] = new html_dom_node($node, $this->dom);
		}
		else
			log_message("debug", "xpath selector is not valid : ".$q." | Is related:".$isRelated);
		
		return $a;
	}
	
}

class html_dom_node
{
	private $node;
	private $dom;
	
	function __construct($nodeItem, &$dom)
	{
		$this->node = $nodeItem;
		$this->dom = $dom;
	}

	/**
	 * Get the inner content of a dom element
	 * @return html string
	 */
	public function getInnerText()
	{
		$innerHTML= '';
		$children = $this->node->childNodes;
		foreach ($children as $child)
		    $innerHTML .= $child->ownerDocument->saveXML( $child );
		
		return $innerHTML; 
	}
	
	/**
	 * Get the outer content of a dom element
	 * @return 
	 */
	public function getOuterText()
	{
		return $this->node->ownerDocument->saveXML( $this->node ); 
	}
	
	/**
	 * Get the value of an attribute
	 * @param string $attributeName
	 * @return value of the attribute
	 */
	public function getAttr($attributeName)
	{
		return $this->node->getAttribute($attributeName);
	}
	
	public function __get($name) 
	{
        switch($name) 
		{
            case 'innertext': return $this->getInnerText();
			case 'outertext': return $this->getOuterText();
            default: return $this->getAttr($name);
        }
    }
	
	/**
	 * Set the inner content of a dom element
	 * @param $value
	 */
	public function setInnerText($value)
	{
		// Create a new document
		$newdoc = new DOMDocument('1.0');
		libxml_use_internal_errors(true);
		
		// make sure the content is utf8
		$value = '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /></head><body><node>'.$value.'</node></body></html>';
		$newdoc->loadHTML('<?xml encoding="UTF-8">'.$value);
		foreach ($newdoc->childNodes as $item)
	    	if ($item->nodeType == XML_PI_NODE)
	        	$newdoc->removeChild($item);
		
		$newdoc->encoding = 'utf-8';
		// Remove the previous child nodes
		$this->_removeChilds($this->node);
		
		// add new nodes
		if(!is_null($newdoc->getElementsByTagName("node")->item(0)))
		{
			foreach($newdoc->getElementsByTagName("node")->item(0)->childNodes as $n)
			{
				$newnode = $this->dom->importNode($n, true);
				
				if($newnode !== false)
				{
					$this->node->appendChild($newnode);
				}
			}
		}
	}
	
	/**
	 * Set the outer value of a node element (replace the current node)
	 * @param $value
	 */
	public function setOuterText($value)
	{
		// Create a new document
		$newdoc = new DOMDocument('1.0');
		$newdoc->formatOutput = true;
		
		// Add some markup
		$newdoc->loadHTML($value);
		
		// The node we want to import to a new document
		$newnode = $this->dom->importNode($newdoc->firstChild, true);
		// Replace the node
		$this->node->parentNode->replaceChild($newnode, $this->node); 
	}
	
	/**
	 * Set the value of a dom element attribute
	 * @param $attributeName
	 * @param $value
	 */
	public function setAttr($attributeName, $value)
	{
		$this->node->setAttribute($attributeName, $value);
	}
	
	public function __set($name, $value) 
	{
        switch($name) 
		{
            case 'innertext': return $this->setInnerText($value);
			case 'outertext': return $this->setOuterText($value);
			default: return $this->setAttr($name, $value);
        }
    }
	
	/**
	 * Find the first child a dom element
	 * @return dom element
	 */
	public function first_child()
	{
		$childs = $this->children();
		return reset($childs);
	}
	
	/**
	 * Find the last child a dom element
	 * @return dom element
	 */
	public function last_child()
	{
		$childs = $this->children();
		return end($childs);
	}
	
	/**
	 * Find all the children of a dom element
	 * @return array of dom element
	 */
	public function children()
	{
		$a = array();
		if($this->node->childNodes->length)
		{
			foreach($this->node->childNodes as $node)
			{
				if($node->nodeType == 1)
					$a[] = new html_dom_node($node, $this->dom);
			}
		}
		return $a;
	}
	
	/**
	 * Find the parent node of a dom element
	 * @return 
	 */
	public function parent()
	{
		$parentNode = new html_dom_node($this->node->parentNode, $this->dom);
		return $parentNode;
	}
	
	/**
	 * Perform a search inside a dom element
	 * @param string $selector
	 * @param int $index [optional]
	 * @return 1 dom element if index is specified or array of dom element if index is null
	 */
	public function find($selector, $index = null)
	{
		$aElements = array();
		
		$dom_xpath = new html_dom_xpath($this->dom);
		
		$xpathSelector = html_dom::cssSelectorToXPath($selector);
		$aElements = $dom_xpath->select($xpathSelector, $this->node);
		
		if($index<0) 
			$index = count($aElements) + $index;
		
		if(is_null($index))
			return $aElements;
		else
			return (isset($aElements[$index]))?$aElements[$index]:array();
	}
	
	private function _removeChilds(&$node)
	{
		while($node->firstChild)
		{
			while ($node->firstChild->firstChild)
			{
				$this->_removeChilds($node->firstChild);
			}
			$node->removeChild($node->firstChild);
		}
	}
	
}
