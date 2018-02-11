<?php

namespace Trypta\Liquid\Responses;

/**
 * Description of ApplicationResponse
 *
 * @package Fashion Interiors
 * @subpackage Core
 * @category Responses
 * @author Sam Jones <sam.jone at freshideas.co.uk>
 */
class ApplicationResponse extends JsonResponse
{
    const KEY_HTML = 'html';
    const KEY_JSON = 'json';
    const KEY_WIDGETS = 'widgets';
    const KEY_ERRORS = 'errors';
    const KEY_DEBUG = 'debug';
    const KEY_NOTIFICATIONS = 'notifications';
    
    public function appendContent($content, $key = self::KEY_HTML)
    {
        parent::appendContent($content, $key);
    }
    
    public function prependContent($content, $key = self::KEY_HTML)
    {
        parent::prependContent($content, $key);
    }
    
    public function setContent($content, $key = self::KEY_HTML)
    {
        parent::setContent($content, $key);
    }
    
    public function setWidgetResponse($content, $widget)
    {
        if (!array_key_exists(self::KEY_WIDGETS, $this->content)) {
            $this->_content[self::KEY_WIDGETS] = array();
        }
        $this->_content[self::KEY_WIDGETS][$widget] = $content;
    }
}
