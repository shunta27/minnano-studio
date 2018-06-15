<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use InvalidArgumentException;

class RepositoryMakeCommand extends GeneratorCommand
{
    protected $type = 'Model';

    protected $name = 'make:repo';
    
    protected $description = 'Create a new Eloquent repository class';

    public function handle()
    {   
        parent::handle();
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories\Eloquent';
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/repository.stub';
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        if ($this->option('model'))
        {
            $replace = $this->buildModelReplacements($replace);
        }

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace)
    {
        $modelClass = $this->parseModel($this->option('model'));

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
        ]);
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model))
        {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (! Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace()))
        {
            $model = $rootNamespace.$model;
        }

        return $model;
    }

    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, ''],
        ];
    }
}
