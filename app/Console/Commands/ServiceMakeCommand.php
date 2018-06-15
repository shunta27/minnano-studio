<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class ServiceMakeCommand extends GeneratorCommand
{
    protected $type = 'Service';

    protected $name = 'make:service';
    
    protected $description = 'Create a new service class';

    public function handle()
    {
        parent::handle();
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/service.stub';
    }

    protected function getOptions()
    {
        return [];
    }
}
