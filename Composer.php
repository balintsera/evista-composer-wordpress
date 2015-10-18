<?php
namespace Evista\Composer;


include_once('vendor/autoload.php');


/*
  Plugin Name: Evista Composer
  Plugin URI:
  Description: Composer integration for WordPress
  Version: 1.0
  Author: Balint Sera
  Author URI: http://evista-agency.com
  */
class EvistaComposer extends DependencyInjector {
    protected $services;

    public function __construct(){
        $this->setUpServices();
        // Inicializations goes here
        parent::__construct();
    }

    /**
     * Add service definitions here:
     *
     */
    public function setUpServices(){
        $this->services = [
            'twig.loader' =>[
                'class' => '\Twig_Loader_Filesystem',
                'arguments' => [__DIR__.'/views']
            ],
            'twig.templating' => [
                'class' => '\Twig_Environment',
                'arguments' => ['@twig.loader']
            ],



            // These are here for testing purposes. Feel free to remove them
            'test.service' => [
                'class' => 'Evista\Composer\Service\TestService',
                'arguments' => ['@test.service.param1', '@test.service.param2']
            ],
            'test.service.param1' => [
                'class' => 'Evista\Composer\Service\TestService2',
                'arguments' => ['@test.service.param2', ['testarray'=>'testparam']]
            ],
            'test.service.param2' => [
                'class' => 'Evista\Composer\Service\TestService3',
                'arguments' => ['stringarg']
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param mixed $services
     * @return EvistaComposer
     */
    public function setServices($services)
    {
        $this->services = $services;

        return $this;
    }


}

$container = new EvistaComposer();

?>