<?php
/**
 * Created by PhpStorm.
 * User: balint
 * Date: 2015. 10. 17.
 * Time: 14:52
 */

namespace Evista\Composer\Service;


class TestService
{
    private $param;
    private $otherParam;

    public function __construct(TestService2 $param, $otherParam = false){
        $this->param = $param;
        $this->otherParam = $otherParam;
    }

    public function echoSomething($likeThis){
        echo $likeThis;
    }
}