<?php
namespace Evista\ComPress;


include_once('vendor/autoload.php');


/*
  Plugin Name: ComPress
  Plugin URI:
  Description: Composer integration for WordPress
  Version: 1.0
  Author: Balint Sera
  Author URI: http://evista-agency.com
  */
class ComPress extends DependencyInjector {
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
            /*
            'form.builder' => [
                'class' => 'AdamWathan\Form\FormBuilder',
            ],
            */

            'twig.loader' =>[
                'class' => '\Twig_Loader_Filesystem',
                'arguments' => [$this->getTwigTemplateDir()]
            ],
            'twig.templating' => [
                'class' => '\Twig_Environment',
                'arguments' => ['@twig.loader']
            ],



            // These are here for testing purposes. Feel free to remove them
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
    }

    private function getTwigTemplateDir(){
        // Get current template for setup twig's view directory
        $currentTheme = wp_get_theme();
        $currentThemeDir = $currentTheme->theme_root.'/'.$currentTheme->template;
        $twigTemplateDir = __DIR__.'/views';
        if(file_exists($currentThemeDir)){
            $twigTemplateDir = $currentThemeDir.'/views';
        }
        return $twigTemplateDir;
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

$container = new ComPress();

?>