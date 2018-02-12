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
   * Basic Configuration Class
   *
   * Parses an ini file into a dot seperated array ie. xxx.yyy
   * xxx is the ini section value, yyy is the key value. further levels can be
   * added to the key ie. xxx.yyy.zzz or xxx.yyy.zzz.aaa ....
   *
   * @method __shutdown Custom Liquid Magic Method called when the application is shutdown and saves configuration
   *
   * @package Liquid Framework
   * @subpackage Core
   * @category Configuration
   * @author Jonesy
   */
  class Configuration {

    /**
     * True if the config has change and has not been saved
     *
     * @access private
     * @var boolean $_requiresSave
     */
    private $requiresSave = false;

    /**
     * True when the configuration has been read and parsed
     *
     * @access private
     * @var boolean $_loaded
     */
    private $loaded = false;

    /**
     * Configuration file path
     *
     * @access private
     * @var string $_filename
     */
    private $filename = null;

    /**
     * Parsed configuration data
     *
     * @access private
     * @var array $_data
     */
    private $data = null;

    /**
     * Request constructor
     *
     * @access public
     * @param string $filename  - Location of configuration file to load
     * @throws \InvalidArgumentException
     */
    public function __construct($filename = null)
    {
      if(!$filename || is_null($filename))
      {
        throw new \InvalidArgumentException('Configuration filename is required');
      }

      $this->filename = $filename;
      if(file_exists($filename))
      {
        $this->load();
      }
    }

    /**
     * Destructor
     *
     * Calls the __shutdown method on destruction
     *
     * @access public
     */
    public function __destruct()
    {
      $this->__shutdown();
    }

    /**
     * Shutdown method, called when the system is shutdown
     *
     * @access public
     */
    public function __shutdown()
    {
      if($this->requiresSave)
      {
        $this->save();
      }
    }

    /**
     * Reloads the configuration from disk, saves first if required
     *
     * @access public
     */
    public function reload()
    {
      if($this->requiresSave)
      {
        $this->save();
      }

      $this->loaded = false;
      $this->load();
    }

    /**
     * Loads the configuration if not loaded already
     *
     * @access public
     * @throws RuntimeException
     */
    public function load()
    {
      if(!$this->loaded)
      {
        $data = parse_ini_file($this->filename, true, INI_SCANNER_NORMAL);

        foreach($data as $name => $section)
        {
          $this->data[$name] = array();
          foreach($section as $key => $value)
          {
            $parts = explode(".", $key);

            if(count($parts) != 2)
            {
              throw new \RuntimeException('Invalid configuration depth: ' . $name . '.' . implode(".", $parts));
            }

            $a = array_shift($parts);
            $b = array_shift($parts);

            if(!array_key_exists($a, $this->data[$name]))
            {
              $this->data[$name][$a] = array();
            }

            $this->data[$name][$a][$b] = $value;
          }
        }

        $this->loaded = true;
      }
    }

    /**
     * Saves the configuration to disk
     *
     * @access public
     */
    public function save()
    {
      $data = array();
      $data[] = "; <?php die; ?>";
      $data[] = "; ";
      $data[] = "; Liquid Framework Application Configuration File";
      $data[] = "";

      foreach($this->data as $a => $section)
      {
        $data[] = "[" . $name . "]";
        foreach($section as $a => $subsection)
        {
          foreach($subsection as $c => $value)
          {
            $data[] = $b . '.' . $c . '="' . $value . '"';
          }
        }

        file_put_contents($this->filename, $data);
        $this->requiresSave = false;
      }
    }

    /**
     * Gets a configuration value
     *
     * @access public
     * @param string $key - dot seperated config key
     * @return mixed - The value
     * @throws \InvalidArgumentException
     */
    public function get($key)
    {
      $parts = explode(".", $key);
      $n = count($parts);

      if($n < 1 || $n > 3)
      {
        throw new \InvalidArgumentException('Configuration key must be between 1 and 3 levels in depth: ' . $key);
      }

      $a = array_shift($parts);
      $b = count($parts) > 0 ? array_shift($parts) : false;
      $c = count($parts) > 0 ? array_shift($parts) : false;

      if(!array_key_exists($a, $this->data))
      {
        throw new \InvalidArgumentException('Configuration key does not exist: ' . $key);
      }

      if(!$b)
      {
        return $this->data[$a];
      }

      if(!array_key_exists($b, $this->data[$a]))
      {
        throw new \InvalidArgumentException('Configuration key does not exist: ' . $key);
      }

      if(!$c)
      {
        return $this->data[$a][$b];
      }

      if(!array_key_exists($c, $this->data[$a][$b]))
      {
        throw new \InvalidArgumentException('Configuration key does not exist: ' . $key);
      }

      return $this->data[$a][$b][$c];
    }

    /**
     * Sets a configuration value
     *
     * @access public
     * @param string $key - dot seperated configuration key
     * @param mixed $value - The value to set
     * @throws \InvalidArgumentException
     */
    public function set($key, $value)
    {
      $parts = explode(".", $key);
      $n = count($parts);

      if($n < 1 || $n > 3)
      {
        throw new \InvalidArgumentException('Configuration key must be between 1 and 3 levels in depth: ' . $key);
      }

      $a = array_shift($parts);
      $b = count($parts) > 0 ? array_shift($parts) : false;
      $c = count($parts) > 0 ? array_shift($parts) : false;

      if(!$b)
      {
        if(!is_array($value))
        {
          throw new \InvalidArgumentException('1st level configiration data must be an array');
        }
        foreach($value as $k => $v)
        {
          $this->set($a . '.' . $k, $v);
        }
      }

      if(!$c)
      {
        if(!is_array($value))
        {
          throw new \InvalidArgumentException('2nd level configiration data must be an array');
        }
        foreach($value as $k => $v)
        {
          $this->set($a . '.' . $b . '.' . $k, $v);
        }
      }

      if($a && $b && $c)
      {
        if(!is_array($this->data))
        {
          $this->data = array();
        }

        if(!array_key_exists($a, $this->data))
        {
          $this->data[$a] = array();
        }

        if(!array_key_exists($b, $this->data[$a]))
        {
          $this->data[$a][$b] = array();
        }
        
        if(!array_key_exists($c, $this->data[$a][$b]))
        {
          $this->data[$a][$b][$c] = null;
        }
        
        $this->requiresSave = $this->data[$a][$b][$c] == $value ? false : true;
        $this->data[$a][$b][$c] = $value;
      }
    }

  }

}
