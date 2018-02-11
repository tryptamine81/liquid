<?php

namespace Trypta\Liquid\UI;

class Element {

    protected $content = array();
    protected $parent = false;

    public function __construct()
    {
        
    }

    public function __toString()
    {
        $content = '';
        foreach ($this->content as $key => $chunk)
        {
            switch (true)
            {
                case is_object($chunk) && is_a($chunk, 'XTemplate'):
                    $content .= $chunk->text('main');
                    break;
                case is_object($chunk):
                    $content .= $chunk->__toString();
                    break;
                default:
                    $content .= $chunk;
                    break;
            }
        }
        return $content;
    }

    public function getContent($parse = false)
    {
        return $this->content;
    }

    public function setContent($content = false)
    {
        $this->content = array();
        $this->append($content);
    }

    /**
     * Sets the parent tag
     * 
     * @access public
     * @param cwElement $parent
     * @return \cwElement
     */
    public function setParent(cwElement $parent)
    {
        $this->_parent = $parent;
        return $this;
    }

    /**
     * Returns this tags parent or false if none
     * 
     * @access public
     * @return \cwElement|false
     */
    public function getParent()
    {
        return $this->_parent;
    }


    public function append($content)
    {
        if (!$content)
        {
            return $this;
        }
        if ($content instanceof cwHtmlTag)
        {
            $content->setParent($this);
        }
        array_push($this->content, $content);
        return $this;
    }
    
    public function prepend($content)
    {
        if (!$content)
        {
            return $this;
        }
        if ($content instanceof cwHtmlTag)
        {
            $content->setParent($this);
        }
        array_unshift($this->content, $content);
        return $this;
    }
    
    public function html($content = false)
    {
        if($content !== false)
        {
            $this->content = array($content);
            return $this;
        } else {
            return $this->content;
        }
    }
    
    public function parent()
    {
        return $this->getParent();
    }

    public function appendTo(cwElement $element)
    {
        $element->appendContent($this);
        return $this;
    }

    public function prependTo(cwElement $element)
    {
        $element->prependContent($this);
        return $this;
    }
    
    public function each(callable $method)
    {
        foreach($this->content as $key => $content)
        {
            $this->Content[$key] = $method($content);
        }
        return $this;
    }
    
}
