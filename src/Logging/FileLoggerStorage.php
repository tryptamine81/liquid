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

/**
 * @since version 0.0.1
 * @author Sam Jones <jonesy at cityvinyl.co.uk>
 */

namespace Trypta\Liquid\Logging;

/**
 * Stores log message in specified file in raw format
 *
 * @package Liquid Framework
 * @subpackage Logging
 * @category Log Storage
 */
class FileLoggerStorage extends AbstractLoggerStorage implements LoggerStorageInterface {

    const DATE_FORMAT = 'Y-m-d H:i:s.v';
    const SEPERATOR = '\t';
    const EOL = '\r\n';           
    
    /**
     * @access protected
     * @var string $file Log file path and filename string
     */
    protected $file = null;

    /**
     * Instantiates the the class
     * 
     * @access public
     * @param string $file The log file path and filename
     */
    public function __construct($file = false)
    {
        $this->file = $file;
    }

    /**
     * Stores a log message in the log file
     * 
     * @access public
     * @param string $message The message to log
     * @throws Exception
     */
    public function store($level, $message)
    {

        $Now = new \DateTime();
        $data = $Now->format(self::DATE_FORMAT) . self::SEPERATOR . $level . self::SEPERATOR . $message . self::EOL;
        
        
        $fp = fopen($this->file, "a");

        $retries = 0;
        $max_retries = 100;
        $lock = false;

        do
        {
            if ($retries > 0)
            {
                usleep(rand(1, 10000));
            }
            $retries++;
            $lock = flock($fp, LOCK_EX);
        } while (!$lock && $retries <= $max_retries);

        if (!$lock)
        {
            throw new Exception('Could not obtain file lock, try saving again.');
        }

        fwrite($fp, $data);
        $unlock = flock($fp, LOCK_UN);
        fclose($fp);
    }

}
