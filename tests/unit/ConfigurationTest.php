<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Trypta\Liquid\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Trypta\Liquid\Configuration as Configuration;
/**
 * Description of ConfigurationTest
 *
 * @author Jonesy
 */
class ConfigurationTest extends TestCase
{
  
  protected $configFile = __DIR__ . DS . 'config.ini.php';
  protected $config = null;

  public function setUp()
  {
    if(file_exists($this->configFile))
    {
      //unlink($this->configFile);
    }
  }
  
  /**
   * Tears down the test
   */
  public function tearDown()
  {
    if(file_exists($this->configFile))
    {
      //unlink($this->configFile);
    }
  }
  
  /**
   * @covers \Trypta\Liquid\Configuration::__construct
   * @covers \Trypta\Liquid\Configuration::set
   */
  public function testCreate()
  {
    //  Create configuration object
    $config = new Configuration($this->configFile);
    
    $this->assertInstanceOf(\Trypta\Liquid\Configuration::class, $config);

    //  Set test configuration
    $config->set('section_a.key_a.value_a', 'aaa');
    $config->set('section_a.key_a.value_b', 'aab');
    $config->set('section_a.key_b.value_a', 'aba');
    $config->set('section_a.key_b.value_b', 'abb');
    $config->set('section_b.key_a.value_a', 'baa');
    $config->set('section_b.key_a.value_b', 'bab');
    $config->set('section_b.key_b.value_a', 'bba');
    $config->set('section_b.key_b.value_b', 'bbb');
    
    return $config;
  }

  /**
   * @depends testCreate
   * @covers \Trypta\Liquid\Configuration::set
   * @param Configuration $config
   * @return Configuration
   */
  public function testSetExceptions(\Trypta\Liquid\Configuration $config)
  {    
    $this->expectException('\InvalidArgumentException');
    $config->set('section_a', array());
    
    $this->expectException('\InvalidArgumentException');
    $config->set('section_a.key_a', null);
    return $config;
  }
  
  /**
   * @depends testCreate
   * @covers \Trypta\Liquid\Configuration::save
   * @param \Trypta\Liquid\Tests\Unit\Trypta\Liquid\Configuration $config
   * @return \Trypta\Liquid\Tests\Unit\Trypta\Liquid\Configuration
   */
  public function testSave(\Trypta\Liquid\Configuration $config)
  {
    //  Save configuration
    $config->save();
    
    $this->assertFileExists($this->configFile);
    
    return $config;
  }
  
  /**
   * @depends testSave
   * @covers \Trypta\Liquid\Configuration::get
   * @covers \Trypta\Liquid\Configuration::load
   * @param Configuration $config
   * @return Configuration
   */
  public function testGet(\Trypta\Liquid\Configuration $config)
  {
    $this->assertFileExists($this->configFile);
  
    $configa = new Configuration($this->configFile);
    $configa->load();
    
    $assertEquals($config->get('section_a.key_a.value_a'), $configa->get('section_a.key_a.value_a'));
    $assertEquals($config->get('section_a.key_a.value_b'), $configa->get('section_a.key_a.value_b'));
    $assertEquals($config->get('section_a.key_b.value_a'), $configa->get('section_a.key_b.value_a'));
    $assertEquals($config->get('section_a.key_b.value_b'), $configa->get('section_a.key_b.value_b'));
    $assertEquals($config->get('section_b.key_a.value_a'), $configa->get('section_b.key_a.value_a'));
    $assertEquals($config->get('section_b.key_a.value_b'), $configa->get('section_b.key_a.value_b'));
    $assertEquals($config->get('section_b.key_b.value_a'), $configa->get('section_b.key_b.value_a'));
    $assertEquals($config->get('section_b.key_b.value_b'), $configa->get('section_b.key_b.value_b'));    
    $assertEquals($config->get('section_a.key_a'), $configa->get('section_a.key_a'));
    $assertEquals($config->get('section_b'), $configa->get('section_b'));
    return $config;
  }
  
  /**
   * @depends testGet
   * @covers \Trypta\Liquid\Configuration::get
   * @covers \Trypta\Liquid\Configuration::load
   * @param Configuration $config
   * @return Configuration
   */
  public function testGetExceptions(\Trypta\Liquid\Configuration $config)
  {
    $this->expectException('\InvalidArgumentException');
    $config->get('a.b.c.d');
    return $config;
  }
  
  /**
   * @depends testGet
   * @covers \Trypta\Liquid\Configuration::reload
   * @param Configuration $config
   * @return Configuration
   */
  public function testReload(\Trypta\Liquid\Configuration $config)
  {
    $config->set('section_a.key_a.value_a', 'AAA');
    
    $this->assertEquals($config->get('section_a.key_a.value_a'), 'AAA');
    
    $config->reload();
    
    $this->assertEquals($config->get('section_a.key_a.value_a'), 'aaa');
    
    return $config;
  }

  
}
