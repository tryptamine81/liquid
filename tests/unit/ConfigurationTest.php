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

  /**
   * Sets up the test
   */
  public function setUp()
  {
    if(file_exists($this->configFile))
    {
      unlink($this->configFile);
    }
  }
  
  /**
   * Tears down the test
   */
  public function tearDown()
  {
    if(file_exists($this->configFile))
    {
      unlink($this->configFile);
    }
  }
  
  /**
   * Test setting and saving of configuration data
   * 
   * @covers Trypta\Liquid\Configuration::set
   * @covers Trypta\Liquid\Configuration::save
   */
  public function testSetSaveConfig()
  {
    //  Create configuration object
    $this->config = new Configuration($this->configFile);
    
    //  Set test configuration
    $this->config->set('section_a.key_a.value_a', 'aaa');
    $this->config->set('section_a.key_a.value_b', 'aab');
    $this->config->set('section_a.key_b.value_a', 'aba');
    $this->config->set('section_a.key_b.value_b', 'abb');
    $this->config->set('section_b.key_a.value_a', 'baa');
    $this->config->set('section_b.key_a.value_b', 'bab');
    $this->config->set('section_b.key_b.value_a', 'bba');
    $this->config->set('section_b.key_b.value_b', 'bbb');
    
    //  Save configuration
    $this->config->save();
    
    $this->assertFileExists($this->configFile);
  }
  
  /**
   * Tests load and get of configuration data
   * 
   * @depends testSetSaveConfig
   * @covers Trypta\Liquid\Configuration::load
   * @covers Trypta\Liquid\Configuration::get
   */
  public function testLoadGetConfig()
  {
    $config = new Configuration($this->configFile);
    
    $this->assertEquals($this->config->get('section_a.key_a.value_a'), $config->get('section_a.key_a.value_a'));
    $this->assertEquals($this->config->get('section_a.key_a.value_b'), $config->get('section_a.key_a.value_b'));
    $this->assertEquals($this->config->get('section_a.key_b.value_a'), $config->get('section_a.key_b.value_a'));
    $this->assertEquals($this->config->get('section_a.key_b.value_b'), $config->get('section_a.key_b.value_b'));
    $this->assertEquals($this->config->get('section_b.key_a.value_a'), $config->get('section_b.key_a.value_a'));
    $this->assertEquals($this->config->get('section_b.key_a.value_b'), $config->get('section_b.key_a.value_b'));
    $this->assertEquals($this->config->get('section_b.key_b.value_a'), $config->get('section_b.key_b.value_a'));
    $this->assertEquals($this->config->get('section_b.key_b.value_b'), $config->get('section_b.key_b.value_b'));
  }
  
}
