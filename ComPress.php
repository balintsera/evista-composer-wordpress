<?php
namespace Evista\ComPress;

use Symfony\Component\Yaml\Yaml;

include_once('vendor/autoload.php');


/*
  Plugin Name: ComPress
  Plugin URI:
  Description: Composer integration for WordPress
  Version: 1.0
  Author: Balint Sera
  Author URI: http://evista-agency.com
  */
class ComPress {
    protected $services;
    private $container;

    public function __construct(){
        // Setup whoops exception handler
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();

        // Initialize services
        $this->setUpServices();

        // Create the lazy service container
        $this->container = new DependencyInjector($this->services);
    }

    /**
     * Add service definitions here:
     *
     */
    public function setUpServices(){
        $this->services = Yaml::parse(file_get_contents(__DIR__.'/services.yml'));
    }

    public static function getTwigTemplateDir(){
        // Get current template for setup twig's view directory
        $currentTheme = wp_get_theme();
        $currentThemeDir = $currentTheme->theme_root.'/'.$currentTheme->template;
        $twigTemplateDir = __DIR__.'/views';
        if(file_exists($currentThemeDir)){
            $twigTemplateDir = $currentThemeDir.'/views';
        }
        return $twigTemplateDir;
    }

    public function get($serviceKey){
        return $this->container->get($serviceKey);
    }


}

$container = new ComPress();

?>