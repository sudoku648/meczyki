<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load(
        'Sudoku648\\Meczyki\\Team\\Domain\\',
        '../../../Domain',
    );

    $services->load(
        'Sudoku648\\Meczyki\\Team\\Frontend\\',
        '../../../Frontend',
    );

    $services->load(
        'Sudoku648\\Meczyki\\Team\\Infrastructure\\',
        '../../../Infrastructure',
    );
};
