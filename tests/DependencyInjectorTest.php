<?php

namespace Evista\ComPress;


/**
 * Created by PhpStorm.
 * User: balint
 * Date: 2015. 10. 15.
 * Time: 12:13
 */
class DependencyInjectorTest extends \PHPUnit_Framework_TestCase
{
    public function testDIArgLoader(){
        $depIn = new ComPress();

        //$depIn->instantiateService('test.service');

        $this->assertInstanceOf('Evista\ComPress\Service\TestService', $depIn->get('test.service'));
    }

}