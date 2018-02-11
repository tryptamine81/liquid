<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use PHPUnit\Framework\TestCase;
use Trypta\Liquid\Environment as Environment;

/**
 * Description of EnvironmentTest
 *
 * @author Jonesy
 */
class EnvironmentTest extends TestCase
{
    public function testEnvironmentSingletonInstantiation()
    {
        $env = Environment::getInstance();
        $this->assertEquals(get_class($env), 'Environment');
    }
}
