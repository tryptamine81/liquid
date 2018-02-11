<?php

/**
 * @since 0.0.1
 * @author Sam Jones <jonesy at citvinyl.co.uk>
 */
namespace Trypta\Liquid\Responses;

/**
 * Sends a file to the browser as a download
 *
 * @package Fashion Interiors
 * @subpackage Core
 * @category Response
 */
class PassThruHttpResponse extends HttpResponse
{
    protected $file = null;
    
    public function __construct($file)
    {
        $this->file = $file;
    }
    
    public function send()
    {
        if (!file_exists($this->file)) {
            $this->setStatus(HttpResponse::STATUS_NOT_FOUND);
            $this->sendHeaders();
            return;
        }
        
        //$this->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
        $this->setHeader('Content-Type', mime_content_type($this->file));
        $this->setHeader('Content-Length', filesize($this->file));
        $this->sendHeaders();
        
        $fp = fopen($this->file, 'r');
        fpassthru($fp);
        fclose($fp);
    }
}
