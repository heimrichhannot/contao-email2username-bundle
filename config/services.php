<?php

declare(strict_types=1);

use HeimrichHannot\Email2UsernameBundle\EventListener\FrontendUserListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(FrontendUserListener::class);

//    $services->load(
//        'HeimrichHannot\\Email2UsernameBundle\\',
//        __DIR__.'/../src/{EventListener}'
//    );
};