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

/* ------------------------------------------------------------------------------------------- */

/**
 * Describes an Environment instance
 * 
 * @since version 0.0.1
 * @author Sam Jones <jones at cityvinyl.co.uk>
 */
namespace Trypta\Liquid;

defined('DS') ? : define('DS', DIRECTORY_SEPARATOR);

/**
 * Describes an Environment instance
 *
 * This class is the base environment class for a liquid application, all custom environment
 * classes should extend this class. This class contains environment type and a system paths
 * registry, it can be extended anyway you wish.
 * 
 * A static call method is provided to make calls to the single instance once initialised,
 * however it does need to be passed a root path, a system path and a library  path when 
 * initialised through either of the following two methods:
 *  
 *  $env = new \Trypta\Liquid\Environment($base_path, $system_path, $library_path);
 *  $env = \Trypta\Liquid\Environment::getInstance($base_path, $system_path, $library_path);
 * 
 * The environment can only be instantiated once and will be registered when either of those
 * methods are used for instantiation. After this, further attempts to instantiate with the 
 * new keyword will result in a \RuntimeException being thrown. ::getInstance() will return 
 * the singleton instance once instantiated.
 * 
 * @package Liquid PHP Application Framework
 * @subpackage Core
 * @category Environment
 * @copyright (c) 2018, Sam Jones
 * 
 * @method null setEnvironmentType(string $type) Sets environment type on singleton instance
 * @method string getEnvironmentType() Returns the environment type
 * @method null setPath(string $id, string $path) Sets a system path by id
 * @method string getPath(string $id) Gets a system path by id
 */
class Environment
{
    use \Trypta\Liquid\Traits\Singleton;

    const ENV_PRODUCTION = 'PRODUCTION';
    const ENV_STAGING = 'STAGING';
    const ENV_TESTING = 'TESTING';
    const ENV_DEVELOPMENT = 'DEVELOPMENT';
    const PATH_ROOT = 'ROOT';
    const PATH_SYSTEM = 'SYSTEM';
    const PATH_LIB = 'LIB';
    const PATH_LOGS = 'LOGS';
    const PATH_DATA = 'DATA';
    const PATH_ASSETS = 'ASSETS';
    const PATH_CONFIG = 'CONFIG';
    const PATH_CACHE = 'CACHE';
    const PATH_BIN = 'BIN';

    /**
     * Contains the system environment type
     *
     * @access private
     * @var string $type
     */
    private $type = self::ENV_DEVELOPMENT;
    
    /**
     * Contains system path locations
     *
     * @access private
     * @var array $paths
     */
    private $paths = array(
        self::PATH_ROOT => null,
        self::PATH_SYSTEM => null,
        self::PATH_LIB => null,
        
        self::PATH_LOGS => 'logs',
        self::PATH_DATA => 'data',
        self::PATH_ASSETS => 'assets',
        self::PATH_CONFIG => 'conf',
        self::PATH_CACHE => 'cache',
        self::PATH_BIN => 'bin'
    );

    /**
     * Static environment access, calls associated method on singleton instance
     *
     * @final
     * @static
     * @access public
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \RuntimeException
     */
    final public static function __callStatic($name, $arguments)
    {
        return call_user_method_array($name, self::getInstance(), $arguments);
    }

    /**
     * Environment class constructor
     *
     * @access public
     * @param string $root Root path (site root: where site index and bootstrap files reside)
     * @param string $system System path (system root: where assets and private data resides)
     * @param string $library Library Path (class library root: PSR/3 namespaced class library)
     * @throws RuntimeException
     */
    public function __construct($root = null, $system = null, $library = null)
    {
        if (empty($root)) {
            if (empty($this->paths[self::PATH_ROOT])) {
                throw new \RuntimeException('Root path is not set');
            }
        } else {
            $this->paths[self::PATH_ROOT] = $root;
        }

        if (empty($system)) {
            if (empty($this->paths[self::PATH_SYSTEM])) {
                throw new \RuntimeException('System path is not set');
            }
        } else {
            $this->paths[self::PATH_SYSTEM] = $system;
        }

        if (empty($library)) {
            if (empty($this->paths[self::PATH_LIB])) {
                throw new \RuntimeException('System path is not set');
            }
        } else {
            $this->paths[self::PATH_LIB] = $library;
        }
    }

    /**
     * Sets the environment type
     *
     * @access public
     * @param string $type Environment::ENV_ constant
     */
    public function setEnvironmentType($type = null)
    {
        $this->type = $type;
        switch ($this->type) {
            case self::ENV_DEVELOPMENT:
            case self::ENV_STAGING:
                ini_set('display_errors', 1);
                error_reporting(E_ALL);
                break;
        }
    }
    
    /**
     * Returns the current environment type
     *
     * @access public
     * @return string
     */
    public function getEnvironmentType()
    {
        return $this->type;
    }

    /**
     * Sets a system path location
     *
     * @access public
     * @param string $id Path ID
     * @param string $path Path relative to PATH_SYSTEM, absolute if it begins with DS
     * @throws \InvalidArgumentException
     */
    public function setPath($id, $path)
    {
        $fixedPaths = array(self::PATH_ROOT, self::PATH_SYSTEM, self::PATH_LIB);
        if (in_array($id, $fixedPaths)) {
            throw new \InvalidArgumentException('Cannot set ' . $id . ' path after instantiation.');
        }

        $this->paths[$id] = strstr($path, 0, 1) == DS 
                ? $path 
                : $this->paths[self::PATH_SYSTEM] . DS . $path;
    }
    
    /**
     * Returns a registered system path
     * 
     * @access public
     * @param string $id
     * @return string
     * @throws \RuntimeException
     */
    public function getPath($id)
    {
        if (!array_key_exists($id, $this->paths)) {
            throw new \RuntimeException('Invalid path ID');
        }
        return substr($this->paths[$id], 0, 1) == DS ? $this->paths[$id] : $this->paths[self::PATH_SYSTEM] . DS . $this->paths[$id];
    }

}
