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
class ComPress {
    protected $services;
    private $container;

    public function __construct(){
        $this->setUpServices();
        // Inicializations goes here
        $this->container = new DependencyInjector($this->services);
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

            /**
             * Swiftmailer mail
             * composer require swiftmailer/swiftmailer
             * https://packagist.org/packages/swiftmailer/swiftmailer
             *
             * Usage:
             * $message = \Swift_Message::newInstance();
             * $message
             * ->setBody('<h1>Test</h1>')
             * ->setSubject("test")
             * ->setFrom('info@e-vista.hu')
             * ->setTo('balint.sera@gmail.com');
             * $mailer->send($message);
             */

            'swiftmailer.transport' => [
                'class' => '\Swift_SmtpTransport',
                'method' => 'newInstance',
                'arguments' => ['127.0.0.1', 25]
            ],

            'swiftmailer.mailer' => [
                'class' => '\Swift_Mailer',
                'method' => 'newInstance',
                'arguments' => ['@swiftmailer.transport']
            ],

            /**
             * Twig templating:
             * composer require twig/twig
             * https://packagist.org/packages/twig/twig
             *
             * Usage: echo $container->get('twig.templating')->render('test.html.twig', ['hehh' => 'Yeah!' ]);

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

    public function get($serviceKey){
        return $this->container->get($serviceKey);
    }


}

$container = new ComPress();

?>