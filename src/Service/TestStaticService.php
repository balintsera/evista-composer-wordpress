<?php
/**
 * Created by PhpStorm.
 * User: balint
 * Date: 2015. 10. 23.
 * Time: 18:49
 */

namespace Evista\ComPress\Service;


use Evista\ComPress\Exception\BadServiceParameterException;

class TestStaticService
{
    public static function init(){
        return new \StdClass;
    }

    public static function initWithArgs($string, $integer, $float){
        if(!is_string($string)){
            throw new BadServiceParameterException('First argument is not a string');
        }

        if(!is_int($integer)){
            throw new BadServiceParameterException('Second argument is not an int');
        }

        if(!is_float($float)){
            throw new BadServiceParameterException('Third argument is not a float');
        }

        return new \StdClass;
    }
}