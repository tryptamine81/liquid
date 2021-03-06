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

namespace Trypta\Liquid\Traits {

    /**
     * Description of Singleton
     *
     * @package Liquid Framework
     * @subpackage Core
     * @category Trait
     * @author Jonesy
     */
    trait Singleton
    {

        /**
         * Singleton instance of this class
         *
         * @static
         * @access private
         * @var Liquid\Application $_instance
         */
        protected static $instance = null;

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
            if (is_null(static::$instance)) {
                $ref = new \ReflectionClass(get_called_class());
                static::$instance = $ref->newInstanceArgs(func_get_args());
            }
            return static::$instance;
        }
        
        /**
         * Remove magic __clone method as singleton
         *
         * @final
         * @access private
         */
        final private function __clone()
        {
        }
        
        /**
         * Remove magic __wakeup method as singleton
         *
         * @final
         * @access private
         */
        final private function __wakeup()
        {
        }
    }
}
