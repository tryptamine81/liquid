<?php

namespace Trypta\Liquid\Responses;

/**
 * Description of JsonResponse
 *
 * @package Fashion Interiors
 * @subpackage Core
 * @category Responses
 * @author Sam Jones <sam.jone at freshideas.co.uk>
 */
class JsonResponse extends HttpResponse
{

    public function __toString()
    {
        return json_encode($this->content);
    }
    
/**
     * Appends a content variable
     * 
     * @access public
     * @param mixed $content The content to append
     * @param string $key The content key
     */
    public function appendContent($content, $key)
    {
        if(!array_key_exists($key, $this->content))
        {
            $this->content[$key] = "";
        }
        $this->content[$key] .= $content;
    }

    /**
     * Prepends a content variable
     * 
     * @access public
     * @param mixed $content The content to prepend
     * @param string $key The content key
     */
    public function prependContent($content, $key)
    {
        if(!array_key_exists($key, $this->content))
        {
            $this->content[$key] = "";
        }
        $this->content[$key] = $content . $this->content[$key];
    }
    
    /**
     * Sets a content variable 
     * 
     * @access public
     * @param mixed $content The content
     * @param string $key The content key
     */
    public function setContent($content, $key)
    {
        $this->content[$key] = $content;
    }

}
