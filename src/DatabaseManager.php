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
     * Liquid Framework Database Manager
     *
     * Used to manage all database connections.
     *
     * Parses a configuration ini file into database hosts and associated databases
     * Parses a table definition ini file into custom Table class as class constants
     *
     * Configuration file format:
     * -------------------------------------------------------------------------
     *
     * The primary database connection ID should be set as DEFAULT, when
     * requesting a database connection DEFAULT will be return if no database
     * ID is specified when this class is invoked
     *
     * Table specification file format:
     * -------------------------------------------------------------------------
     *
     * When specifing a table in an sql query, the table name (Table::constant) should be
     * passed through then ::table($table) method. This will prepend the default database name
     * if a database name is not specified in the Table::constant value. It will also insert
     * the table prefix value for current connection.
     *
     * @example conf/databases.ini.php
     * @example conf/db_tables.ini.php
     *
     * @todo Update documentation for configuration file format specification
     *
     * @package Liquid Framework
     * @subpackage Core
     * @category Database
     * @author Sam Jones <jonesy at cityvinyl.co.uk>
     * @since version 0.0.1
     */
    final class DatabaseManager
    {
        const DEFAULT_DATABASE = 'DEFAULT';

        /**
         * Database Configuration File Path
         *
         * @access private
         * @var string $file_config
         */
        private $file_config = 'databases.ini.php';

        /**
         * Database Table Definitions File Path
         *
         * @access private
         * @var string $file_tables
         */
        private $file_tables = 'db_tables.ini.php';

        /**
         * Map of database ID's to database name and connection references
         *
         * @access private
         * @var array $_databaseHostMap
         */
        private $_databaseHostMap = array();

        /**
         * Array of host PDO connections
         *
         * @access private
         * @var array $_connections
         */
        private $_connections = array();

        /**
         * Private constructor
         *
         * @access private
         * @param string $config_file Configuration File Path
         * @param string $tables_file Table Definition File Path
         */
        public function __construct($file_config, $file_tables)
        {
            $this->_parseConfigFile($file_config);
            $this->_parseTablesFile($file_tables);
        }

        /**
         * Returns a database connection by ID
         *
         * @access public
         * @param string $id Database ID
         * @return \PDO
         */
        public function __invoke($id = false)
        {
            $id = $id ? : self::DEFAULT_DATABASE;
            return $this->_hosts[$this->_databaseHostMap[$id]]['conn'];
        }

        /**
         * Pass all un-declared method calls to default database for convenience
         *
         * @access public
         * @param string $name Method name
         * @param array $arguments Arguments for the method
         */
        public function __call($name, $arguments)
        {
            call_user_method_array($name, $this(), $arguments);
        }

        /**
         * Parse the database connection configuration file
         *
         * @access private
         * @param string $file The file to parse
         * @throws RuntimeException
         */
        private function _parseConfigFile($file)
        {
            if (!file_exists($file)) {
                throw new \RuntimeException('Database configuration file does not exists: ' . $file);
            }
            $this->_file_config = $file;
            $config = parse_ini_file($file, true, INI_SCANNER_NORMAL);
            $databases = array();

            foreach ($config as $section => $data) {
                if (array_key_exists('type', $data)) {
                    //  section is connection data
                    $this->_connections[$section] = new \PDO($this->_getDsn($data), $data['user'], $data['pass']);
                    $this->_databaseHostMap[$section] = array('name' => $data['name'], 'host' => $this->_connections[$section]);
                } else {
                    $databases[$section] = $data;
                }
            }
            $hasDefault = false;
            foreach ($databases as $id => $data) {
                //  section is database data
                $this->_databaseHostMap[$id] = array('name' => $data['name'], 'conn' => $this->_connections[$data['conn']]);
                if ($id == self::DEFAULT_DATABASE) {
                    $hasDefault = true;
                }
            }

            if (!$hasDefault) {
                throw new RuntimeException('No default database defined');
            }
        }

        /**
         * Returns a valid PDO dsn string
         *
         * @access private
         * @param array $data Configuration data
         * @return string
         */
        private function _getDsn($data)
        {
            switch ($data['type']) {
                case 'mysql':
                default:
                    $dsn = 'mysql:dbname=' . $data['name'] . ';host=' . $data['host'];
                    break;
            }
            return $dsn;
        }

        /**
         * Parse the table definition file and dynamically creates the table class
         *
         * @access private
         * @param string $file Path to table definitions file
         * @throws RuntimeException
         */
        private function _parseTablesFile($file)
        {
            if (!file_exists($file)) {
                throw new \RuntimeException('Database tables file does not exists: ' . $file);
            }

            $this->_file_tables = $file;
            $tables = parse_ini_file($file, true, INI_SCANNER_NORMAL);

            $tableClass = "class Table { ";
            foreach ($tables as $name => $data) {
                if (array_key_exists('table', $data)) {
                    $tableClass .= "const " . $name . " = \"" . $data['table'] . "\";";
                }
            }
            $tableClass .= "}";
            eval($tableClass);
        }
    }

}
