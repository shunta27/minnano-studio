<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class RepositoryInterfaceMakeCommand extends GeneratorCommand
{
    protected $type = 'Model';

    protected $name = 'make:repo_interface';
    
    protected $description = 'Create a new repository interface class';

    public function handle()
    {
        parent::handle();
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/repository_interface.stub';
    }

    protected function getOptions()
    {
        return [];
    }
}
