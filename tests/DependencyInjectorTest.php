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
        $services = [
            'test.service' => [
                'class' => 'Evista\ComPress\Service\TestService',
                'arguments' => ['@test.service.param1', '@test.service.param2']
            ],
            'test.service.param1' => [
                'class' => 'Evista\ComPress\Service\TestService2',
                'arguments' => ['@test.service.param2', ['testarray'=>'testparam']]
            ],
            'test.service.param2' => [
                'class' => 'Evista\ComPress\Service\TestService3',
                'arguments' => ['stringarg']
            ],
        ];
        $depIn = new DependencyInjector($services);

        //$depIn->instantiateService('test.service');

        $this->assertInstanceOf('Evista\ComPress\Service\TestService', $depIn->get('test.service'));
    }

    /**
     * Testing abstract method injecting (abstract class injecting as with symfony/form
     * @throws Exception\UnknownServiceException
     */
    public function testAbstractClassLoader(){
        $services = [
            'static' => [
                'class' => 'Evista\ComPress\Service\TestStaticService',
                'method' => 'init'
            ]
        ];
        $depIn = new DependencyInjector($services);
        $this->assertInstanceOf('\StdClass', $depIn->get('static'));
    }

    

}