<?php

/**
 * Describes a HttpResponse
 *
 * @since version 0.0.1
 * @author Sam Jones <jonesy at cityvinyl.co.uk>
 */
namespace Trypta\Liquid\Responses;

/**
 * Description of HtmlResponse
 *
 * @package Liquid Framework
 * @subpackage Core
 * @category Response
 */
class HttpResponse extends BaseResponse implements ResponseInterface
{

    //  Informational Response Statuses
    const STATUS_CONTINUE                        = 100;
    const STATUS_SWITCHING_PROTOCOLS             = 101;
    const STATUS_PROCESSING                      = 102;
    const STATUS_EARLY_HINTS                     = 103;
    //  Success Response Statuses
    const STATUS_OK                              = 200;
    const STATUS_CREATED                         = 201;
    const STATUS_ACCEPTED                        = 202;
    const STATUS_NON_AUTH_INFO                   = 203;
    const STATUS_NO_CONTENT                      = 204;
    const STATUS_RESET_CONTENT                   = 205;
    const STATUS_PARTIAL_CONTENT                 = 206;
    const STATUS_MULTI_STATUS                    = 207;
    const STATUS_ALREADY_REPORTED                = 208;
    const STATUS_IM_USED                         = 226;
    //  Redirection Response Statuses
    const STATUS_MULTIPLE_CHOICES                = 300;
    const STATUS_MOVED_PERMANENTLY               = 301;
    const STATUS_FOUND                           = 302;
    const STATUS_SEE_OTHER                       = 303;
    const STATUS_NOT_MODIFIED                    = 304;
    const STATUS_USE_PROXY                       = 305;
    const STATUS_SWITCH_PROXY                    = 306;
    const STATUS_TEMPORARY_REDIRECT              = 307;
    const STATUS_PERMANENT_REDIRECT              = 308;
    //  Client Error Response Statuses
    const STATUS_BAD_REQUEST                     = 400;
    const STATUS_UNAUTHORIZED                    = 401;
    const STATUS_PAYMENT_REQUIRED                = 402;
    const STATUS_FORBIDDEN                       = 403;
    const STATUS_NOT_FOUND                       = 404;
    const STATUS_METHOD_NOT_ALLOWED              = 405;
    const STATUS_NOT_ACCEPTABLE                  = 406;
    const STATUS_PROXY_AUTH_REQUIRED             = 407;
    const STATUS_REQUEST_TIMEOUT                 = 408;
    const STATUS_CONFLICT                        = 409;
    const STATUS_GONE                            = 410;
    const STATUS_LENGTH_REQUIRED                 = 411;
    const STATUS_PRECONDITION_FAILED             = 412;
    const STATUS_PAYLOAD_TOO_LARGE               = 413;
    const STATUS_URI_TOO_LONG                    = 414;
    const STATUS_UNSUPPORTED_MEDIA_TYPE          = 415;
    const STATUS_RANGE_NOT_SATISFIABLE           = 416;
    const STATUS_EXPECTATION_FAILED              = 417;
    const STATUS_IM_A_TEAPOT                     = 418;
    const STATUS_MISDIRECTED_REQUEST             = 421;
    const STATUS_UNPROCESSABLE_ENTITY            = 422;
    const STATUS_LOCKED                          = 423;
    const STATUS_FAILED_DEPENDENCY               = 424;
    const STATUS_UPGRADE_REQUIRED                = 426;
    const STATUS_PRECONDITION_REQUIRED           = 428;
    const STATUS_TOO_MANY_REQUESTS               = 429;
    const STATUS_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    const STATUS_UNAVAILABLE_FOR_LEGAL_REASONS   = 451;
    //  Server Error Response Statuses
    const STATUS_INTERNAL_SERVER_ERROR           = 500;
    const STATUS_NOT_IMPLEMENTED                 = 501;
    const STATUS_BAD_GATEWAY                     = 502;
    const STATUS_SERVICE_UNAVAILABLE             = 503;
    const STATUS_GATEWAY_TIMEOUT                 = 504;
    const STATUS_HTTP_VERSION_NOT_SUPPORTED      = 505;
    const STATUS_VARIANT_ALSO_NEGOTIATES         = 506;
    const STATUS_INSUFFICIENT_STORAGE            = 507;
    const STATUS_LOOP_DETECTED                   = 508;
    const STATUS_NOT_EXTENDED                    = 510;
    const STATUS_NETWORK_AUTHENTICATION_REQUIRED = 511;

    protected static $_statusText = array(
        //  Informational Response Statuses
        self::STATUS_CONTINUE                        => 'Continue',
        self::STATUS_SWITCHING_PROTOCOLS             => 'Switch Protocols',
        self::STATUS_PROCESSING                      => 'Processing',
        self::STATUS_EARLY_HINTS                     => 'Early Hints',
        //  Success Response Statuses
        self::STATUS_OK                              => 'OK',
        self::STATUS_CREATED                         => 'Created',
        self::STATUS_ACCEPTED                        => 'Accepted',
        self::STATUS_NON_AUTH_INFO                   => 'Non-Authroitative Information',
        self::STATUS_NO_CONTENT                      => 'No Content',
        self::STATUS_RESET_CONTENT                   => 'Reset Content',
        self::STATUS_PARTIAL_CONTENT                 => 'Partial Content',
        self::STATUS_MULTI_STATUS                    => 'Multi-Status',
        self::STATUS_ALREADY_REPORTED                => 'Already Reported',
        self::STATUS_IM_USED                         => 'IM Used',
        //  Redirection Response Statuses
        self::STATUS_MULTIPLE_CHOICES                => 'Multiple Choices',
        self::STATUS_MOVED_PERMANENTLY               => 'Moved Permanently',
        self::STATUS_FOUND                           => 'Found',
        self::STATUS_SEE_OTHER                       => 'See Other',
        self::STATUS_NOT_MODIFIED                    => 'Not Modified',
        self::STATUS_USE_PROXY                       => 'Use Proxy',
        self::STATUS_SWITCH_PROXY                    => 'Switch Proxy',
        self::STATUS_TEMPORARY_REDIRECT              => 'Temporary Redirect',
        self::STATUS_PERMANENT_REDIRECT              => 'Permanent Redirect',
        //  Client Error Response Statuses
        self::STATUS_BAD_REQUEST                     => 'Bad Request',
        self::STATUS_UNAUTHORIZED                    => 'Unauthorized',
        self::STATUS_PAYMENT_REQUIRED                => 'Payment Required',
        self::STATUS_FORBIDDEN                       => 'Forbidden',
        self::STATUS_NOT_FOUND                       => 'Not Found',
        self::STATUS_METHOD_NOT_ALLOWED              => 'Method Not Allowed',
        self::STATUS_NOT_ACCEPTABLE                  => 'Not Acceptable',
        self::STATUS_PROXY_AUTH_REQUIRED             => 'Proxy Authentication Required',
        self::STATUS_REQUEST_TIMEOUT                 => 'Request Timeout',
        self::STATUS_CONFLICT                        => 'Conflict',
        self::STATUS_GONE                            => 'Gone',
        self::STATUS_LENGTH_REQUIRED                 => 'Length Required',
        self::STATUS_PRECONDITION_FAILED             => 'Precondition Failed',
        self::STATUS_PAYLOAD_TOO_LARGE               => 'Payload Too Large',
        self::STATUS_URI_TOO_LONG                    => 'URI Too Long',
        self::STATUS_UNSUPPORTED_MEDIA_TYPE          => 'Unsupported Media Type',
        self::STATUS_RANGE_NOT_SATISFIABLE           => 'Range Not Satisfiable',
        self::STATUS_EXPECTATION_FAILED              => 'Expectation Failed',
        self::STATUS_IM_A_TEAPOT                     => "I'm a teapot",
        self::STATUS_MISDIRECTED_REQUEST             => 'Misdirected Request',
        self::STATUS_UNPROCESSABLE_ENTITY            => 'Unprocessable Entity',
        self::STATUS_LOCKED                          => 'Locked',
        self::STATUS_FAILED_DEPENDENCY               => 'Failed Dependency',
        self::STATUS_UPGRADE_REQUIRED                => 'Upgrade Required',
        self::STATUS_PRECONDITION_REQUIRED           => 'Precondition Required',
        self::STATUS_TOO_MANY_REQUESTS               => 'Too Many Requests',
        self::STATUS_REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request Header Fields Too Large',
        self::STATUS_UNAVAILABLE_FOR_LEGAL_REASONS   => 'Unavailable For Legal Reasons',
        //  Server Error Response Statuses
        self::STATUS_INTERNAL_SERVER_ERROR           => 'Internal Server Error',
        self::STATUS_NOT_IMPLEMENTED                 => 'Not Implemented',
        self::STATUS_BAD_GATEWAY                     => 'Bad Gateway',
        self::STATUS_SERVICE_UNAVAILABLE             => 'Service Unavailable',
        self::STATUS_GATEWAY_TIMEOUT                 => 'Gateway Timeout',
        self::STATUS_HTTP_VERSION_NOT_SUPPORTED      => 'HTTP Version Not Supported',
        self::STATUS_VARIANT_ALSO_NEGOTIATES         => 'Variant Also Negotiates',
        self::STATUS_INSUFFICIENT_STORAGE            => 'Insufficient Storage',
        self::STATUS_LOOP_DETECTED                   => 'Loop Detected',
        self::STATUS_NOT_EXTENDED                    => 'Not Extended',
        self::STATUS_NETWORK_AUTHENTICATION_REQUIRED => 'Network Authentication Required'
    );

    /**
     * Valid HTTP Response Header Keys
     *
     * @static
     * @access protected
     * @var array $_validHeaders
     */
    protected static $_validHeaders = array(
        //  Standard HTTP Headers
        'Access-Control-Allow-Origin', 'Access-Control-Allow-Credentials',
        'Access-Control-Expose-Headers', 'Access-Control-Max-Age',
        'Access-Control-Allow-Methods', 'Access-Control-Allow-Headers',
        'Accept-Path', 'Accept-Ranges', 'Age', 'Allow', 'Alt-Svc',
        'Cache-Control', 'Connection', 'Content-Disposition',
        'Content-Encoding', 'Content-Language', 'Content-Length',
        'Content-Location', 'Content-MD5', 'Content-Range', 'Content-Type',
        'Date', 'ETag', 'Expires', 'Last-Modified', 'Link', 'Location',
        'P3P', 'Pragma', 'Proxy-Authenticate', 'Public-Key-Pins',
        'Retry-After', 'Server', 'Set-Cookie', 'Strict-Transport-Security',
        'Trailer', 'Transfer-Encoding', 'Tk', 'Upgrade', 'Vary', 'Via',
        'Warning', 'WWW-Authenticate', 'X-Frame-Options',
        //  Common non-standard headers
        'Content-Security-Policy', 'X-COntent-Security-Policy',
        'X-WebKit-CSP', 'Refresh', 'Status', 'Timing-Allow-Origin',
        'Upgrade-Insecure-Requests', 'X-Content-Duration',
        'X-Content-Type-Options', 'X-Powered-By', 'X-Request-ID',
        'X-Correlation-ID', 'X-UA-Compatible', 'X-XSS-Protection'
    );

    /**
     * Contains a list of HTTP response headers to be sent
     *
     * @access protected
     * @var array $_headers
     */
    protected $_headers = array();

    /**
     * Contains a list of cookies to send with this response
     *
     * @access protected
     * @var array $_cookies
     */
    protected $_cookies = array();

    /**
     * Contains a list of string content to send with this response
     *
     * @access protected
     * @var array $_content
     */
    protected $_content = array();

    /**
     * HTTP Response Status Code
     *
     * Use class constants: HttpResponse::STATUS_*
     *
     * @access protected
     * @var int $_status
     */
    protected $_status = self::STATUS_OK;

    /**
     * True if the headers have already been sent
     *
     * @access protected
     * @var boolean $_headersSent
     */
    protected $_headersSent = false;
    
    /**
     * Minify Output Flag
     *
     * @access protected
     * @var boolean $minify
     */
    protected $minify = false;
    
    /**
     * Get response as string
     *
     * @access public
     */
    public function __toString()
    {
        return implode(null, $this->_content);
    }

    /**
     * Send and flush the content buffer
     *
     * @access public
     */
    public function flush()
    {
        $this->send();
        $this->clear();
    }
    
    /**
     * Clears the content buffer
     *
     * @access public
     */
    public function clear()
    {
        $this->_content = array();
    }
    
    /**
     * Sends headers and then content via parent::send()
     *
     * @access public
     */
    public function send()
    {
        $this->sendHeaders();
        echo $this->minify ? \Minify_HTML::minify($this->__toString()) : $this->__toString();
    }

    /**
     * Sends the HTTP response headers if not sent already
     *
     * @todo extend cookie implementation to use all php setcookie variables
     *
     * @access public
     */
    public function sendHeaders()
    {
        //  return now if headers already sent
        if ($this->_headersSent) {
            return;
        }

        //  Send HTTP status
        header("HTTP/1.1 " . $this->_status . ' ' . self::$_statusText[$this->_status]);

        //  Send headers
        foreach ($this->_headers as $key => $value) {
            header($key . ": " . $value);
        }

        //  Send cookies
        foreach ($this->_cookies as $name => $info) {
            setcookie($name, $info['value'], $info['expire']);
        }

        //  Mark headers as sent
        $this->_headersSent = true;
    }

    /**
     * Sets the HTTP status to be sent with this response
     *
     * Use class constants: HttpResponse::STATUS_*
     *
     * @access public
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->_status = $status;
    }

    /**
     * Sets a HTTP response header value, only valid HTTP response headers
     * should be used, an InvalidArgumentException will be throw if the
     * header name is invalid and/or not supported
     *
     * @access public
     * @param string $key HTTP header name
     * @param mixed $value Value to set
     * @throws \InvalidArgumentException
     */
    public function setHeader($key, $value)
    {
        if ($this->_headersSent) {
            throw new RuntimeException('Cannot set header, headers already sent!');
        }
        if (!in_array($key, self::$_validHeaders)) {
            throw new \InvalidArgumentException('Invalid HTTP Header: ' . $key);
        }
        $this->_headers[$key] = $value;
    }

    /**
     * Un-sets a HTTP response header for this response
     *
     * @access public
     * @param string $key HTTP Header name
     */
    public function unsetHeader($key)
    {
        if ($this->_headersSent) {
            throw new RuntimeException('Cannot unset header, headers already sent!');
        }
        if (array_key_exists($key, $this->_headers)) {
            unset($this->_headers[$Key]);
        }
    }

    /**
     * Returns the array of header data
     *
     * @access public
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     * Sets a cookie value for this response
     *
     * @access public
     * @param string $name Cookie name
     * @param mixed $value Cookie value
     * @param string $expires Cookie Expires
     */
    public function setCookie($name, $value, $expires)
    {
        if ($this->_headersSent) {
            throw new RuntimeException('Cannot set cookie, headers already sent!');
        }
        $this->_cookies[$name] = array('value' => $value, 'expires' => $expires);
    }

    /**
     * Un-sets a cookie value for this response
     *
     * @access public
     * @param string $name
     */
    public function unsetCookie($name)
    {
        if ($this->_headersSent) {
            throw new RuntimeException('Cannot unset cookie, headers already sent!');
        }
        if (array_key_exists($name, $this->_cookies)) {
            unset($this->_cookies[$name]);
        }
    }

    /**
     * Appends a content variable
     *
     * @access public
     * @param mixed $content The content to append
     */
    public function appendContent($content)
    {
        array_push($this->_content, $content);
    }

    /**
     * Prepends a content variable
     *
     * @access public
     * @param mixed $content The content to prepend
     */
    public function prependContent($content)
    {
        array_unshift($this->_content, $content);
    }

    /**
     * Sets a content variable
     *
     * @access public
     * @param mixed $content The content
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }
    
    /**
     * Turn minify on (true) or off (false)
     *
     * @access public
     * @param boolean $bool On/Off
     */
    public function minify($bool=true)
    {
        $this->minify = (bool) $bool;
    }
}
