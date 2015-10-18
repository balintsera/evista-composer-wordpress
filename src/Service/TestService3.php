<?php
/**
 * Created by PhpStorm.
 * User: balint
 * Date: 2015. 10. 17.
 * Time: 14:54
 */

namespace Evista\Composer\Service;


class TestService3
{
    private $stringArgument;
    public function __construct($stringArgument){
        $this->stringArgument = $stringArgument;
    }
}