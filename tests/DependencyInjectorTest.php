<?php

namespace Evista\Composer;

use Evista\MailchimpAPI\ValueObject\RequestFactory;
use Evista\MailchimpAPI\Exception\RequestException;

/**
 * Created by PhpStorm.
 * User: balint
 * Date: 2015. 10. 15.
 * Time: 12:13
 */
class DependencyInjectorTest extends \PHPUnit_Framework_TestCase
{
    public function testDIArgLoader(){
        $depIn = new EvistaComposer();

        //$depIn->instantiateService('test.service');

        $this->assertInstanceOf('Evista\Composer\Service\TestService', $depIn->get('test.service'));
    }

}