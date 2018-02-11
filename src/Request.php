<?php

/*
 * Copyright (C) 2018 Jonesy
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace Trypta\Liquid {

    /**
     * Description of Request
     *
     * @author Jonesy
     */
    class Request {

        use Traits\Singleton;

        private $_headers = array();
        private $_data = array();
        private $_get = array();
        private $_server = array();
        private $_env = array();
        private $_files = array();
        private $_cookies = array();

        public function __construct()
        {
            $this->_headers = function_exists('apache_request_headers') ? apache_request_headers() : $this->_apache_request_headers();
            $this->_data = $_REQUEST;
            $this->_get = $_GET;
            $this->_server = $_SERVER;
            $this->_env = $_ENV;
            $this->_cookies = $_COOKIE;
            $this->_files = $_FILES;
        }

        public function getHeader($key)
        {
            if (!array_key_exists($key, $this->_headers))
            {
                throw new InvalidArgumentException('Invalid HTTP header: ' . $key);
            }
            return $this->_headers[$key];
        }

        public function has($key)
        {
            return array_key_exists($key, $this->_data);
        }

        public function get($key, $filter = FILTER_DEFAULT, $options = array())
        {
            if (!$this->has($key))
            {
                throw new InvalidArgumentException('Request data not set: ' . $key);
            }
            return filter_var($this->_data[$key], $filter, $options);
        }

        public function getArray(array $keys, array $definition, $add_empty = true)
        {
            return filter_var_array($keys, $definition, $add_empty);
        }

        public function getOrSet($key, $value, $filter = FILTER_DEFAULT, $options = array())
        {
            if (!$this->has($key))
            {
                $this->set($key, $value);
            }
            return filter_var($this->_data[$key], $filter, $options);
        }

        public function hasQueryStringParam($key)
        {
            return array_key_exists($key, $this->_get);
        }

        public function getQueryStringParam($key, $filter = FILTER_DEFAULT, $options = array())
        {
            if (!$this->hasQueryStringParam())
            {
                throw new \InvalidArgumentException('Invalid query string parameter or parameter not set: ' . $key);
            }
            return filter_var($this->_get[$key], $filter, $options);
        }

        public function getServer($key)
        {
            if (!array_key_exists($key, $this->_server))
            {
                throw new \InvalidArgumentException('Invalid server variable key: ' . $key);
            }
            return $this->_server[$key];
        }

        public function getCookie($key, $filter = FILTER_DEFAULT, $options = array())
        {
            if (!$this->hasCookie($key))
            {
                throw new \InvalidArgumentException('Cookie value does not exist: ' . $key);
            }
            return filter_var($this->_cookies[$key], $filter, $options);
        }

        public function hasCookie($key)
        {
            return array_key_exists($key, $this->_cookies);
        }

        public function getEnv($key)
        {
            if (!$this->hasEnv($key))
            {
                throw new \InvalidArgumentException('Environment variable key is invalid: ' . $key);
            }
            return $this->_env[$key];
        }

        public function hasEnv($key)
        {
            return array_key_exists($key, $this->_env);
        }

        public function getFile($key)
        {
            if (!$this->hasFile($key))
            {
                throw new \InvalidArgumentException('File does not exists in request: ' . $key);
            }
            return $this->_files[$key];
        }

        public function hasFile($key)
        {
            return array_key_exists($key, $this->_files);
        }
        
        public function getUri($incQueryString = false)
        {
            $q_parts = explode("?", $this->getServer('REQUEST_URI'));
            return $incQueryString ? $this->getServer('REQUEST_URI') : array_shift($q_parts);
        }
        
        public function getRelativeUri($slug, $incQueryString = false)
        {
            $url = $this->getUri($incQueryString);
            $len = strlen($slug);
            return substr($url, 0, $len) == $slug ? substr($url, $len) : $url;
        }
        
        public function isAjax()
        {
            return array_key_exists('X-Requested-With', $this->_headers) && $this->_headers['X-Requested-With'] == 'XMLHttpRequest';
        }

        private function _apache_request_headers()
        {
            $arh = array();
            $rx_http = '/\AHTTP_/';
            foreach ($_SERVER as $key => $val)
            {
                if (preg_match($rx_http, $key))
                {
                    $arh_key = preg_replace($rx_http, '', $key);
                    $rx_matches = array();
                    // do some nasty string manipulations to restore the original letter case
                    // this should work in most cases
                    $rx_matches = explode('_', $arh_key);
                    if (count($rx_matches) > 0 and strlen($arh_key) > 2)
                    {
                        foreach ($rx_matches as $ak_key => $ak_val)
                        {
                            $rx_matches[$ak_key] = ucfirst($ak_val);
                        }
                        $arh_key = implode('-', $rx_matches);
                    }
                    $arh[$arh_key] = $val;
                }
            }
            return( $arh );
        }

    }

}