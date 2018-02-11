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
    protected $env = null;
    
    public function setUp()
    {
        $this->env = Environment::getInstance();
    }
    
    public function testEnvironmentSingletonInstantiation()
    {
        $this->assertEquals(get_class($this->env), 'Trypta\Liquid\Environment');
    }
}
