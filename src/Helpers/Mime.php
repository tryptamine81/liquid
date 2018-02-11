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

namespace Trypta\Liquid\Helpers {

    /**
     * Description of Mime
     *
     * @package Liquid Framework
     * @subpackage Core
     * @Category Helper
     * @author Jonesy
     */
    class Mime
    {
        const APACHE_MIME_TYPES_URL = 'http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types';
        const DEFINITIONS_FILE = 'mime.ini.php';

        /**
         * Mime type definition list, loaded from cached ini file
         *
         * @static
         * @access protected
         * @var array $_mimeDefinitions
         */
        protected static $_mimeDefinitions = null;
        
        /**
         * Returns the MIME type of the file as a string
         *
         * @static
         * @access public
         * @param string $file Path to the file
         * @return string
         */
        public static function getFileMime($file)
        {
            $q_parts = explode("?", $file);
            $noqs = array_shift($q_parts);
            
            $f_parts = explode(".", $noqs);
            $ext = array_pop($f_parts);
            
            return self::getExtensionMime($ext);
        }
        
        /**
         * Returns the MIME type of the file extension as a string
         *
         * @static
         * @access public
         * @param string $ext The file extension
         * @return string
         */
        public static function getExtensionMime($ext)
        {
            if (is_null(self::$_mimeDefinitions) || count(self::$_mimeDefinitions['types']) == 0) {
                self::_loadDefinitions();
            }
            
            if (array_key_exists($ext, self::$_mimeDefinitions['types'])) {
                return self::$_mimeDefinitions['types'][$ext];
            }
            return 'NOT_FOUND';
        }
        
        /**
         * Loads the cached definition file, if it does not exist, create it
         *
         * @static
         * @access private
         */
        private static function _loadDefinitions()
        {
            if (!file_exists(__DIR__ . DS . self::DEFINITIONS_FILE)) {
                static::_generateUpToDateMimeArray();
            } else {
                self::$_mimeDefinitions = parse_ini_file(__DIR__ . DS . self::DEFINITIONS_FILE, true, INI_SCANNER_NORMAL);
            }
            
            if (count(self::$_mimeDefinitions['types']) == 0) {
                static::_generateUpToDateMimeArray();
            }
        }
        
        /**
         * Generates the mime cache file from the public list located at
         * self::APACHE_MIME_TYPES_URL
         *
         * @static
         * @access private
         * @param string $url
         */
        private static function _generateUpToDateMimeArray($url = false)
        {
            $url = $url ?: self::APACHE_MIME_TYPES_URL;
            
            $ini_file = __DIR__ . DS . self::DEFINITIONS_FILE;
            $ini_data = array('; <?php die; ?:', '', '[types]');
            
            $contents = file_get_contents($url);
            $lines = explode("\n", $contents);
            self::$_mimeDefinitions = array();
            
            foreach ($lines as $line) {
                //  Ignore lines beginning with a '#'
                if (substr($line, 0, 1) == '#') {
                    continue;
                }
                
                //  Find whitespace seperated strings
                $match = preg_match_all('#([^\s]+)#', $line, $matches);
                
                //  If no match, continue
                if (!$match || !isset($matches[1])) {
                    continue;
                }
                
                //  Count matches on line
                $c = count($matches[1]);
                
                //  If less than 2 matches, continue
                if ($c < 2) {
                    continue;
                }
                
                // Iterate matches, first match is type, remaining matches
                // are the associated file extensions
                for ($i = 1; $i < $c; $i++) {
                    $ini_data[] = $matches[1][$i] . "=\"" . $matches[1][0] . "\"";
                    self::$_mimeDefinitions[$matches[1][$i]] = $matches[1][0];
                }
            }
            
            //  Write mime cache file
            file_put_contents($ini_file, implode("\r\n", $ini_data));
        }
    }

}
