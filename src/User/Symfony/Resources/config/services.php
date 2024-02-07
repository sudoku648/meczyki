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
        'Sudoku648\\Meczyki\\User\\Cli\\',
        '../../../Cli',
    );

    $services->load(
        'Sudoku648\\Meczyki\\User\\Domain\\',
        '../../../Domain',
    );

    $services->load(
        'Sudoku648\\Meczyki\\User\\Frontend\\',
        '../../../Frontend',
    );

    $services->load(
        'Sudoku648\\Meczyki\\User\\Infrastructure\\',
        '../../../Infrastructure',
    );
};
