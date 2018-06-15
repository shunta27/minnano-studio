<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class ModelMakeCommand extends GeneratorCommand
{
    protected $type = 'Model';

    protected $name = 'make:repo_model';
    
    protected $description = 'Create a new Eloquent model class';

    protected $model_name;

    public function handle()
    {
        $this->model_name = Str::studly(class_basename($this->argument('name')));
    
        $this->createRepository();
        
        parent::handle();
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories\Eloquent\Models';
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/model.stub';
    }

    protected function createRepository()
    {
        $this->call('make:repo_interface', [
            'name' => "{$this->model_name}RepositoryInterface",
        ]);

        $this->call('make:repo', [
            'name' => "{$this->model_name}Repository",
            '--model' => $this->model_name,
        ]);
    }

    protected function getOptions()
    {
        return [];
    }
}
