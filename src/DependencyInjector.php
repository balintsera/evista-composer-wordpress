<?php
/**
 * Created by PhpStorm.
 * User: balint
 * Date: 2015. 10. 17.
 * Time: 13:52
 */

namespace Evista\ComPress;

use Evista\ComPress\Exception\UnknownServiceException;
use Evista\ComPress\Exception\BadServiceParameterException;

abstract class DependencyInjector implements Injectable
{

    private $loadedServices = [];

    abstract public function getServices();

    public function __construct(){
        array_walk($this->getServices(), function($serviceDesctription, $serviceKey){
            $this->instantiateService($serviceKey);
        });
        //var_dump($this->loadedServices);
    }

    /**
     * Get a service class
     * Lazy load
     * @param $key
     * @return mixed
     * @throws UnknownServiceException
     */
    public function get($key){
        if(!array_key_exists($key, $this->loadedServices)){
            throw new UnknownServiceException('Unknown service: '.$key);
        }

        // Lazy loading: if already
        if(is_object($this->loadedServices[$key])){
            return $this->loadedServices[$key];
        }

        // Eval if only a eval command
        if(is_string($this->loadedServices[$key])){
            $this->loadedServices[$key] = eval($this->loadedServices[$key]);
            return $this->loadedServices[$key];
        }

        throw new UnknownServiceException('Service '.$key.' not an object or neighter a string, cannot instantiate it: '.print_r($this->loadedServices[$key], true));
    }

    /**
     * Genarate instantialization expression for a given service
     * @param $key
     */
    public function instantiateService($key){
        // If not instantiated already (every arugment surfacec second time as a service also)
        if(array_key_exists($key, $this->loadedServices)){
           return;
        }

        // Get this service's arguments
        $serviceArgs = $this->getServices()[$key]['arguments'];

        // convert arguments to a string list for use in eval
        $evalParams = $this->convertArgumentsToExpression($serviceArgs);

        // Generate full instatialization expression to use later
        $instantiateClassExpression = 'return new '.$this->getServices()[$key]['class'].'('.$evalParams.');';

        // Lazy loading: only instantiate with eval when requested
        $this->loadedServices[$key] = $instantiateClassExpression;
    }

    /**
     * Convert all arguments to a valid php expression which can be evaled any time later
     * @param $serviceArgs
     * @return null|string
     * @throws BadServiceParameterException
     */
    private function convertArgumentsToExpression($serviceArgs){
        if($serviceArgs === null) return;
        $evalParams = null;
        foreach($serviceArgs as $serviceKey){
            if(isset($evalParams)){
                $evalParams .= ',';
            }

            // Array
            if(is_array($serviceKey)){
                $evalParams .= $this->processArrayServiceArgument($serviceKey);
            }

            // Service: beginning with a @
            elseif(strpos($serviceKey, '@') !== false){
                $evalParams .= $this->processServiceArgument($serviceKey);
            }

            // String
            elseif(is_string($serviceKey)){
                $evalParams .= $this->processStringServiceArgument($serviceKey);
            }

        }
        return $evalParams;
    }

    /**
     * Process service argument - convert to a valid php expression
     * @param $serviceKey
     * @return string
     */
    private function processServiceArgument($serviceKey){
        // Remove @ from the beginning of the string
        $serviceKey = substr($serviceKey,1);

        $initializeCommandPattern = '$this->get("%s")';

        // instantiate or add
        if(! array_key_exists($serviceKey, $this->loadedServices)) {
            // instantiate this service recursively if it's a service, otherwise add them literary
            $this->instantiateService($serviceKey);

        }
        $evalParams = sprintf($initializeCommandPattern, $serviceKey);

        return $evalParams;
    }

    /**
     * Process a string argument
     * @param $serviceKey
     * @return string
     */
    private function processStringServiceArgument($serviceKey){
        return '"'.$serviceKey.'"';
    }

    /**
     * Process Array argument - convert to a valid php expression
     * @param $serviceKey
     * @return null|string
     * @throws BadServiceParameterException
     */
    private function processArrayServiceArgument($serviceKey){
        $evalParams = null;
        foreach($serviceKey as $arrayKey => $arrayValue){
            if(isset($evalParams)){
                $evalParams .= ',';
            }
            if(is_array($arrayValue) || is_object($arrayValue)){
                throw new BadServiceParameterException('Only primitive values supported as array members');
            }
            $evalParams .= sprintf('"%s"=>"%s"', $arrayKey, $arrayValue);
        }

        $evalParams = "[".$evalParams."]";
        return $evalParams;
    }
}