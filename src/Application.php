<?php

/*
 * Copyright (C) 2018 Sam Jones
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
    
    defined('DS') ?: define('DS', DIRECTORY_SEPARATOR);

    use Environment as Environment;
    use Configuration as Configuration;
    use Request as Request;
    use Session as Session;
    use DatabaseManager as DatabaseManager;
    use Router as Router;

    /**
     * Main Application Class
     *
     * Custom applications should extend this class.
     *
     * It holds all primary environment, configuration, database, router,
     * request and response objects. Concrete implementations of this class will
     * be passed to the request handler on instantiation.
     *
     * @package Liquid Framework
     * @subpackage Core
     * @category Application
     * @author Sam Jones <jonesy at cityvinyl.co.uk>
     * @since 0.0.1
     */
    abstract class Application
    {
        
        /**
         * Magic Singleton Method Name
         */
        const MAGIC_METHOD_SINGLETON = '__singleton';

        /**
         * Magic Shutdown Method Name
         */
        const MAGIC_METHOD_SHUTDOWN = '__shutdown';

        /**
         * Liquid Framework Namespace
         */
        const LIQUID_NAMESPACE = 'Liquid';

        /**
         * Singleton instance of this class
         *
         * @static
         * @access private
         * @var Liquid\Application $_instance
         */
        protected static $_instance = null;

        /**
         * Application Configuration File Path
         *
         * @static
         * @access public
         * @var string $applicationConfigFile
         */
        public static $applicationConfigFile = 'configuration.ini.php';

        /**
         * Environment Configuration File Path
         *
         * @static
         * @access public
         * @var string $environmentConfigFile
         */
        public static $enviromentConfigFile = 'environment.ini.php';

        /**
         * Database Configuration File Path
         *
         * @static
         * @access public
         * @var string $databaseConfigFile
         */
        public static $databaseConfigFile = 'databases.ini.php';

        /**
         * Database Table Definition File Path
         *
         * @static
         * @access public
         * @var string $databaseTablesFile
         */
        public static $databaseTablesFile = 'db_tables.ini.php';

        /**
         * Logger Object
         *
         * @access public
         * @var Psr\Log\LoggerInterface $logger Logger instance
         */
        public $logger = null;
        
        /**
         * Environment Object
         *
         * @access public
         * @var Liquid\Core\Environment $environment System Environment Object
         */
        public $environment = null;

        /**
         * Configuration Object
         *
         * @access public
         * @var Liquid\Core\Configuration $config System Configuration Object
         */
        public $config = null;

        /**
         * Request Object
         *
         * @access public
         * @var Liquid\Core\Request $request System Request Object
         */
        public $request = null;

        /**
         * Session Object
         *
         * @access public
         * @var Liquid\Core\Session $session System Session Object
         */
        public $session = null;

        /**
         * Database Manager
         *
         * @access public
         * @var Liquid\Core\DatabaseManager $db Primary Database Manager
         */
        public $db = null;

        /**
         * Router Object
         *
         * @access public
         * @var Liquid\Core\Router $router System Router Object
         */
        public $router = null;

        /**
         * Shutdown method register
         *
         * @access private
         * @var array(Callable) $_shutdownStack
         */
        private $_shutdownStack = array();

        /**
         * Main application entry point, use Application::main on concreate class
         *
         * @final
         * @static
         * @access public
         */
        final public static function main()
        {
            static::getInstance()->run()->out()->shutdown();
        }

        /**
         * Returns singleton instance of class
         *
         * @final
         * @static
         * @access public
         * @return \Liquid\Application
         */
        final public static function getInstance()
        {
            if (is_null(static::$_instance)) {
                $ref = new \ReflectionClass(get_called_class());
                static::$_instance = $ref->newInstanceArgs(func_get_args());
            }
            return static::$_instance;
        }
        
        /**
         *
         * @param type $name
         * @return type
         */
        final public function __get($name)
        {
            switch ($name) {
                case 'logger':
                    return $this->environment->getLogger();
            }
        }
        
        /**
         *
         * @param type $name
         * @param type $arguments
         */
        final public function __call($name, $arguments)
        {
        }
        
        /**
         * Remove magic __clone method as singleton class
         *
         * @final
         * @access private
         */
        final private function __clone()
        {
        }
        
        /**
         * Remove magic __wakeup method as singleton class
         *
         * @final
         * @access private
         */
        final private function __wakeup()
        {
        }
        
        /**
         * Application constructor
         *
         * @access public
         */
        final public function __construct()
        {
            ob_start();
            try {
                //  Run before initialisation hook
                $this->beforeInitialisation();
                
                //  Create environment object
                $this->environment = Environment::getInstance();

                //  Create system configuration object
                $this->config = new Configuration($this->environment->getPath(Environment::PATH_CONFIG) . DS . self::$applicationConfigFile);

                //  Get request object instance
                $this->request = Request::getInstance();
                
                //  Run before router start hook
                $this->beforeRouterStart();

                //  Create and start router object
                $this->router = Router::getInstance($this);

                //  Run after initialisation hook
                $this->afterInitialisation();
            } catch (Exception $ex) {
                $this->handleException($ex);
            }
            $startup_output = ob_get_flush();
        }

        /**
         * Main application run method, handles session, database and routing
         * and errors
         *
         * @access public
         * @return self
         */
        final public function run()
        {
            ob_start();
            try {
                //  Run before database start hook
                $this->beforeDatabaseStart();

                //  Start database
                $db_config = $this->environment->getPath(Environment::PATH_CONFIG) . DS . self::$databaseConfigFile;
                $db_tables = $this->environment->getPath(Environment::PATH_CONFIG) . DS . self::$databaseTablesFile;
                $this->db = new DatabaseManager($db_config, $db_tables);

                //  Create session object
                $this->session = new Session($this->config->get(Session::CONFIG_SECTION));

                //  Run before session start hook
                $this->beforeSessionStart();

                //  Start Sesssion
                $this->session->start();
            } catch (Exception $ex) {
                // Start up Exception
                $this->handleException($ex);
                exit;
            }

            try {
                $this->beforeRoutingStart();
                $this->router->route();
                $this->response = $this->router->getResponse();
            } catch (Exception $ex) {
                $this->router->handleException($ex);
            }
            ob_end_flush();
            return $this;
        }
        
        /**
         * Outputs the response
         *
         */
        final public function out()
        {
            $this->response->send();
            return $this;
        }

        /**
         * Application shutdown function
         *
         * @final
         * @access public
         */
        final public function shutdown()
        {
            ob_start();
            try {
                foreach ($this->_shutdownStack as $key => $callable) {
                    call_user_func($callable);
                }
            } catch (\Exception $ex) {
                $this->handleException($ex);
            }
            $shutdown_content = ob_end_flush();
        }

        /**
         * Registers a shutdown handler
         *
         * @final
         * @access public
         * @param string|object $co - Class name or instance
         * @param string $method - Method name to call on class or instance
         */
        final public function registerShutdownHandler($co, $method)
        {
            array_unshift($this->_shutdownStack, array($co, $method));
        }

        /**
         * Application Exception Handler
         *
         * @access protected
         * @param \Exception $ex The exception to handle
         */
        protected function handleException(\Exception $ex)
        {
            $this->response = new HttpResponse();
        }

        /**
         * Called before the application has initialised
         *
         * @access protected
         */
        protected function beforeInitialisation()
        {
        }

        /**
         * Called after the application has initialised
         *
         * @access protected
         */
        protected function afterInitialisation()
        {
        }

        /**
         * Called before the router is started
         *
         * @access protected
         */
        protected function beforeRouterStart()
        {
        }

        /**
         * Called before the database is started
         *
         * @access protected
         */
        protected function beforeDatabaseStart()
        {
            $this->router->beforeDatabaseStart();
        }

        /**
         * Called before the session is started
         *
         * @access protected
         */
        protected function beforeSessionStart()
        {
            $this->router->beforeSessionStart();
        }

        /**
         * Called before routing begins
         *
         * @access protected
         */
        protected function beforeRoutingStart()
        {
            $this->router->beforeRoutingStart();
        }
    }

}
