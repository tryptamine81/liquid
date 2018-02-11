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

namespace Trypta\Liquid\Logging;

use Psr\Log\LogLevel as LogLevel;

/**
 * Liquid Framework PSR-3 compliant Abstract Logger Class
 *
 * Base logger all Liquid Framework and defines additional logger actions
 *
 * @since version 0.0.1
 * @author Sam Jones <jonesy at cityvinyl.co.uk>
 */
abstract class AbstractLogger implements \Psr\Log\LoggerInterface
{
    
    /**
     * Logger storage engine
     *
     * @access protected
     * @var LoggerStorageInterface $storage
     */
    protected $storage = null;

    /**
     * Base constructor sets the storage engine instance
     *
     * @access public
     * @param \Liquid\Core\Logging\LoggerStorageInterface $storage Logger Storage Engine Instance
     */
    public function __construct(LoggerStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Log an alert event
     *
     * @access public
     * @param string $message The message string
     * @param array $context Context variables to insert into message
     */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Log a critical event
     *
     * @access public
     * @param string $message The message string
     * @param array $context Context variables to insert into message
     */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Log a debug event
     *
     * @access public
     * @param string $message The message string
     * @param array $context Context variables to insert into message
     */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Log an emergency event
     *
     * @access public
     * @param string $message The message string
     * @param array $context Context variables to insert into message
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Log an error event
     *
     * @access public
     * @param string $message The message string
     * @param array $context Context variables to insert into message
     */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Log an info event
     *
     * @access public
     * @param string $message The message string
     * @param array $context Context variables to insert into message
     */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     *
     * @param string $level Log level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        $message = $this->interpolate($message, $context);
        $this->storage->store($level, $message);
    }

    /**
     * Log a notice event
     *
     * @access public
     * @param string $message The message string
     * @param array $context Context variables to insert into message
     */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Log a warning event
     *
     * @access public
     * @param string $message The message string
     * @param array $context Context variables to insert into message
     */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @access public
     * @param string $message
     * @param array $context
     * @return string
     */
    public function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
