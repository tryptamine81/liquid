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
     * Description of Session
     *
     * @author Jonesy
     */
    class Session
    {
        const CONFIG_SECTION = 'system.session';

        private $_config_defaults = array(
            'domain' => null,
            'save_path' => '',
            'lifetime' => 2592000
        );

        use Traits\Singleton;

        private $app = null;
        public $config = array();

        public function __construct(array $config = array())
        {
            $this->config = array_merge($this->_config_defaults, $config);
        }

        public function start()
        {
            session_save_path($this->config['save_path']);
            session_set_cookie_params($this->config['save_path'], '/', $config['domain'], false, false);
            session_start();
        }

        public function __get($name)
        {
            if ($name == 'data') {
                return $_SESSION;
            }
        }
    }

}
