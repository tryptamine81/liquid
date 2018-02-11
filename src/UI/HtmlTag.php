<?php

    namespace Trypta\Liquid\UI;

    class HtmlTag extends Element
    {
    /**
     * Invalid HTML tags
     * @var string[] 
     */
        protected static $invalidTags = array('body', 'head', 'html');
        
    /**
     * Valid HTML tags
     * @var string[] 
     */
        protected static $validTags = array(
            'a', 'abbr', 'address', 'area', 'article', 'aside', 'audio', 
            'b', 'base', 'bdi', 'bdo', 'blockquote', 'br', 'button', 
            'canvas', 'caption', 'cite', 'code', 'col', 'colgroup', 
            'data', 'datalist', 'dd', 'del', 'dfn', 'div', 'dl', 'dt',
            'em', 'embed', 
            'fieldset', 'figcaption', 'figure', 'footer', 'form', 
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'hr',
            'i', 'iframe', 'img', 'input', 'ins', 
            'kbd', 'keygen', 
            'label', 'legend', 'li', 'link', 
            'main', 'map', 'mark', 'meta', 'meter', 
            'nav', 'noscript', 
            'object', 'ol', 'optgroup', 'option', 'output', 
            'p', 'param', 'pre', 'progress', 'q', 
            'rb', 'rp', 'rt', 'rtc', 'ruby',
            's', 'samp', 'script', 'section', 'select', 'small', 'source', 'span', 'string', 'style', 'sub', 'sup',
            'table', 'tbody', 'td', 'template', 'textarea', 'tfoot', 'th', 'thead', 'time', 'title', 'tr', 'track', 
            'u', 'ul', 
            'var', 'video',
            'wbr'            
        );
        
    /**
     * Self-closing HTML tags
     * @var string[] 
     */
        protected static $selfClosingTags = array(
          'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'ketgen', 'link', 'meta', 'param', 'source', 'track', 'wbr'  
        );
        
    /**
     * This objects tag
     * @var string
     */
        protected $_tag;
        
    /**
     * This objects attributes
     * @var array 
     */
        protected $_attributes = array();
        protected $_parent = false;
        
        public static function _($tag = false, cwElement $parent = null)
        {
            return new self($tag, $parent);
        }
        
    /**
     * HtmlTag construct
     * 
     * @access public
     * @param string $tag (Required) - Must be a valid HTML tag eg. 'a' or 'div'
     * @throws InvalidArgumentException
     * @return \cwHtmlTag
     */
        public function __construct($tag = false, cwElement $parent = null)
        {
            if(strstr($tag, ".") || strstr($tag, '#'))
            {
                $parts = explode("#", $tag);
                $tParts = explode(".", $parts[0]);
                $iParts = count($parts) > 1 ? explode(".", $parts[1]) : false;
                
                $tag = $tParts[0];
                if(count($tParts) > 1)
                {
                    for($i = 1; $i < count($tParts); $i++)
                    {
                        $this->addClass($tParts[$i]);
                    }
                }
                if($iParts)
                {
                    $this->setId($iParts[0]);
                    if(count($iParts) > 1)
                    {
                        for($i = 1; $i < count($iParts); $i++)
                        {
                            $this->addClass($iParts[$i]);
                        }
                    }
                }
            }
            
            if(in_array($tag, static::$invalidTags))
            {
                throw new InvalidArgumentException('<' . $tag . '> tags have been disabled in cwHtmlTag class');
            }
            
            if(!in_array($tag, static::$validTags))
            {
                throw new InvalidArgumentException('<' . $tag . '> tag is an invalid HTML tag');
            }
            
            $this->_tag = $tag;
            
            if(!is_null($parent))
            {
                $parent->appendContent($this);
            }
        }
        
    /**
     * Generates the HTML output for the tag
     * 
     * @access public
     * @return string
     */
        public function __toString()
        {
            $html = "<" . $this->_tag;
            
            foreach($this->_attributes as $attr => $param)
            {
                $value = is_array($param) ? implode(" ", $param) : $param;
                $html .= ' ' . $attr . '="' . $value . '"';
            }
            
            if($this->_isSelfClosing())
            {
                $html .= " />";
                return $html;
            }
            $html .= ">" . parent::__toString() . "</" . $this->_tag . ">";
            return $html;
        }

    /**
     * Adds an attribute value
     * 
     * @access public
     * @param string $attr - Attribute to add to
     * @param mixed $param - Value to be added
     * @return \cwHtmlTag
     */
        public function addAttribute($attr, $param)
        {
            if(!array_key_exists($attr, $this->_attributes))
            {
                $this->_attributes[$attr] = array();
            }
            
            if(is_array($this->_attributes[$attr]))
            {
                if(!in_array($param, $this->_attributes[$attr]))
                {
                    array_push($this->_attributes[$attr], $param);
                }
            } 
            else 
            {
                $this->_attributes[$attr] .= " " . $param;
            }    
            return $this;
        }
        
    /**
     * Sets an attribute
     * 
     * @access public
     * @param string $attr - Attribute to set
     * @param mixed $param - Value to set
     * @return \cwHtmlTag
     */
        public function setAttribute($attr, $param)
        {
            $this->_attributes[$attr] = $param;
            return $this;
        }
        
    /**
     * Checks if attribute contains a value
     * If $param is not supplied: Checks if attribute is set at all
     * 
     * @access public
     * @param string $attr - attribute to check
     * @param mixed $param (optional) - value to check for
     * @return boolean
     */
        public function hasAttribute($attr, $param = false)
        {
            if(!array_key_exists($attr, $this->_attributes))
            {
                return false;
            }

            if(!$param)
            {
                return true;
            }

            return is_array($this->_attributes[$attr]) 
                ? in_array($param, $this->_attributes[$attr]) 
                : preg_match("\b" . $param . "\b", $this->_attributes[$attr]);
        }
        
    /**
     * Removes an attribute value, if no value supplied: removes attribute
     * 
     * @access public
     * @param string $attr - attribute to remove [from]
     * @param mixed $param - (optional) value to remove
     * @return \cwHtmlTag
     */
        public function removeAttr($attr, $param = false)
        {
            if(array_key_exists($attr, $this->_attributes))
            {
                if(!$param)
                {
                    usnet($this->_attributes[$param]);
                    return $this;
                }
                
                if(is_array($this->_attributes[$attr]))
                {
                    if(in_array($param, $this->_attributes[$attr]))
                    {
                        $n = array();
                        foreach($this->_attributes[$attr] as $c)
                        {
                            if($c != $param)
                            {
                                $n[] = $c;
                            }
                        }
                        $this->_attributes[$attr] = $n;
                    }
                } else {
                    $this->_attributes[$attr] = str_replace(array(" " . $param, " " . $param . " ", $param . " "), "", $this->_attributes[$attr]);
                    //$this->_attributes[$attr] = preg_replace("\b" . $param . "\b", "", $this->_attributes[$attr]);
                }
            }
            return $this;
        }
        
    /**
     * Sets the tag elements id
     * 
     * @access public
     * @param string $id
     * @return \cwHtmlTag
     */
        public function setId($id)
        {
            $this->setAttribute('id', $id);
            return $this;
        }
        
    /**
     * Gets the tag elements id
     * 
     * @access public
     * @return mixed
     */
        public function getId()
        {
            return array_key_exists('id', $this->_attributes) ? $this->_attributes['id'] : false;
        }
        
    /**
     * Adds a class to this tag element
     * 
     * @access public
     * @param string $param - class name to add
     * @return \cwHtmlTag
     */
        public function addClass($param)
        {
            if(strstr(" ", $param))
            {
                $classes = explode(" ", $param);
                foreach($classes as $c)
                {
                    $this->addClass($c);
                }
            } else {
                $this->addAttribute('class', $param);
            }
            return $this;
        }
        
    /**
     * Removes a class from this tag element
     * 
     * @access public
     * @param string $param - class name to remove
     * @return \cwHtmlTag
     */
        public function removeClass($param = false)
        {
            $this->removeAttr('class', $param);
            return $this;
        }
        
    /**
     * Checks if class attribute contains supplied class value
     * If no value supplied, checks if class is set at all
     * 
     * @access public
     * @param string $param - (optional) class name to check for
     * @return mixed
     */
        public function hasClass($param = false)
        {
            return $this->hasAttribute('class', $param);
        }
        
        public function addTag($tag, $prepend = false)
        {
            $t = new self($tag);
            $prepend ? $this->prependContent($t) : $this->appendContent($t);
            return $t;
        }
       

        
    /**
     * Is the tag self closing?
     * Self closing tags do not output their children tags html
     * 
     * @access protected
     * @return bool
     */
        protected function _isSelfClosing()
        {
            return in_array($this->_tag, static::$selfClosingTags);
        }
        
        public function find($selector)
        {
            $elements = array();
            $selectors = explode(" ", $selector);
            
            $parents = array($this);
            
            foreach($selectors as $selector)
            {
                $selector_data = $this->extractSelectorData($selector);
                
                $parents = $this->getMatchingElements($parents, $selector_data);
                
            }
            return $parents;
        }
        
        public function getTag()
        {
            return $this->_tag;
        }
        
        protected function getMatchingElements($parents, $selector_data)
        {
            $matches = array();
            foreach($parents as $parent)
            {
            
                $match = $this->tagClassIdMatch($parent, $selector_data);

                if($match)
                {
                    array_push($matches, $parent);
                }
                
                $Children = $parent->getChildren();
                
                if($Children && is_array($Children) && count($Children) > 0)
                {
                    foreach($Children as $Child)
                    {
                        $_matches = $this->getMatchingElements($Child, $selector_data);
                        
                        if(count($_matches) > 0)
                        {
                            foreach($_matches as $_match)
                            {
                                array_push($matches, $_match);
                            }
                        }
                    }
                }
            }
            return $matches;
        }
        
        protected function tagClassIdMatch(cwHtmlTag $element, $data)
        {
            if($data['tag'] && $data['tag'] != $element->getTag())
            {
                return false;
            }
            
            if($data['id'] && $data['id'] != $element->getId())
            {
                return false;
            }
            
            if(count($data['classes']) > 0)
            {
                foreach($data['classes'] as $class)
                {
                    if(!$element->hasClass($class))
                    {
                        return false;
                    }
                }
            }
            return true;
        }
        
        protected function extractSelectorData($selector)
        {
           
            $selector_data =  array('tag' => false, 'id' => false, 'classes' => array());
            if(strstr($selector, ".") || strstr($selector, '#'))
            {
                $parts = explode("#", $selector);
                $tParts = explode(".", $parts[0]);
                $iParts = count($parts) > 1 ? explode(".", $parts[1]) : false;

                $selector_data['tag'] = trim($parts[0]) == "" ? false : array_shift($parts);
                $selector_data['classes'] = array();
                
                foreach($tParts as $part)
                {
                    array_push($selector_data['classes'], $part);
                }
                
                if($iParts && is_array($iParts))
                {
                    $selector_data['id'] = array_shift($iParts);
                    foreach($iParts as $part)
                    {
                        array_push($selector_data['classes'], $part);
                    }
                }
                
            }
            return $selector_data;
        }
                
    }
