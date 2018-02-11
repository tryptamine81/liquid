<?php

namespace Trypta\Liquid\Tests\Unit;

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
    
    /**
     * @covers Trypta\Liquid\Environment::setEnvironmentType
     * @covers Trypta\Liquid\Environment::getEnvironmentType
     */
    public function testEnvironmentType()
    {
        $this->env->setEnvironmentType(Environment::ENV_DEVELOPMENT);
        $this->assertEquals($this->env->getEnvironmentType(), Environment::ENV_DEVELOPMENT);
        
        $this->env->setEnvironmentType(Environment::ENV_STAGING);
        $this->assertEquals($this->env->getEnvironmentType(), Environment::ENV_STAGING);
        
        $this->env->setEnvironmentType(Environment::ENV_PRODUCTION);
        $this->assertEquals($this->env->getEnvironmentType(), Environment::ENV_PRODUCTION);
        
        $this->env->setEnvironmentType(Environment::ENV_TESTING);
        $this->assertEquals($this->env->getEnvironmentType(), Environment::ENV_TESTING);
    }
    
    /**
     * @covers Trypta\Liquid\Environment::getPath
     * @covers Trypta\Liquid\Environment::setPath
     */
    public function testPaths()
    {
        $this->env->setPath(Environment::PATH_ASSETS, 'assets_test');
        $this->assertEquals($this->env->getPath(Environment::PATH_ASSETS), PATH_SYSTEM . DIRECTORY_SEPARATOR . 'assets_test');
        
        $this->expectException(\InvalidArgumentException::class);
        $this->env->setPath(Environment::PATH_SYSTEM, 'invalid');
        
        $this->expectException(\RuntimeException::class);
        $this->env->getPath('invalid');
    }
}
