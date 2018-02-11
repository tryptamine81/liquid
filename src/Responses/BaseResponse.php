<?php

/**
 * Describes the BaseResponse class
 *
 * @since version 0.0.1
 * @author Sam Jones <jonesy at cityvinyl.co.uk>
 */
namespace Trypta\Liquid\Responses;

/**
 * Description of BaseResponse
 *
 * @package Liquid Framework
 * @subpackage Core
 * @category Responses
 */
abstract class BaseResponse
{
    public function send()
    {
        echo $this->__toString();
    }
    
    abstract public function __toString();
}
