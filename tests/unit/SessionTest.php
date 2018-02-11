<?php

namespace Trypta\Liquid\Tests\Unit;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use PHPUnit\Framework\TestCase;
use Trypta\Liquid\Session as Session;

/**
 * Description of EnvironmentTest
 *
 * @author Jonesy
 */
class SessionTest extends TestCase
{
    protected $session;
    
    public function setUp()
    {
        $this->session = Session::getInstance();
        $this->session->start();
    }
    
    /**
     * @covers Trypta\Liquid\Session::__get
     */
    public function testSession()
    {
        $_SESSION['test'] = 'value';
        $this->assetEquals($this->session->data['test'], 'value');
    }
}
