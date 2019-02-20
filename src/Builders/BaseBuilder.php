<?php

namespace AbCreative\LaravelMaker\Builders;

use AbCreative\LaravelMaker\Resource;
use AbCreative\LaravelMaker\UtilsTrait;
use Illuminate\Console\Command;

abstract class BaseBuilder
{
    use UtilsTrait;

    protected $adminName = 'admin';

    /**
     * Placeholder for instance of Resource.
     * @var object
     */
    public $resource = null;

    /**
     * Placeholder for instance of CommandInterface.
     * @var object
     */
    protected $command = null;

    /**
     * @param Command $command
     * @param resource $resource
     */
    public function __construct(Command $command, Resource $resource)
    {
        $this->command = $command;

        $this->resource = $resource;

        $this->init();
    }

    /**
     * Process the stub files for this builder.
     *
     * @param string $resource_dir
     * @param string $admin_dir
     * @return bool
     */
    protected function processStubs()
    {
        foreach ($this->stubs() as $stub => $file) {
            $file = $this->getOutputPath($file);

            $this->createParentDirectoryPath($file);

            if ($this->canWriteOutputFile($file)) {
                $stub = $this->getStubPath($stub);

                $output = $this->replacePlaceholders($stub);

                if ($this->writeOutputFile($file, $output)) {
                    $this->command->info('Created: '.$file);
                } else {
                    $this->command->error('Unable to create: '.$file);
                }
            } else {
                $this->command->info('File exists - skipping: '.$file);
            }
        }
    }

    /**
     * Replace the placeholders in the stubs array (defined in config/maker.php) to get the output filenames.
     *
     * @param string $file
     */
    protected function getOutputPath($file)
    {
        $matches = [];

        /*
         * Get an array of {tags} in the stub filename string
         */
        preg_match_all("/\{(.+?)\}/", $file, $matches);

        if (! empty($matches)) {

            /*
             * $matches[1] will return the string inside the {foo}  e.g. foo
             */
            foreach ($matches[1] as $placeholder) {
                $file = str_replace('{'.$placeholder.'}', $this->resource->placeholders[$placeholder], $file);
            }
        }

        $file = str_replace('//', '/', $file);

        $file = str_replace('//', '/', $file);

        return $file;
    }

    /**
     * Check if we can write the output file
     * We should not overwrite an existing file unless the --force flag is used.
     *
     * @param string $file
     */
    protected function canWriteOutputFile($file)
    {
        if (! $this->command->force && $this->command->filesystem->exists(base_path($file))) {
            return false;
        }

        return true;
    }

    /**
     * Get the path to the stub file.
     * @todo this needs to take into account overloaded paths
     *
     * @param string $stub
     */
    protected function getStubPath($stub)
    {
        return __DIR__.'/../stubs/'.$stub;
    }

    /**
     * Replace the placeholders in the stub file.
     *
     * @param string $stub
     * @return string
     */
    protected function replacePlaceholders($stub)
    {
        $output = $this->command->filesystem->get($stub);

        $output = $this->replacePlaceholderMethods($output);

        foreach ($this->resource->placeholders as $key => $value) {
            $placeholder = $this->resource->placeholder($key);

            $output = str_replace($placeholder, $value, $output);
        }

        return $output;
    }

    protected function replacePlaceholderMethods($output)
    {
        $pattern = '/__METHOD.(.*)__/';

        preg_match_all($pattern, $output, $matches);

        if (! empty($matches[1])) {
            $builders = config('maker.field-builders');

            foreach ($matches[1] as $var) {
                $method = '\\'.str_replace(' ', '', ucwords(strtolower(str_replace('.', ' ', $var)))).'Builder';

                foreach ($builders as $builder) {
                    if (ends_with($builder, $method)) {
                        $builderObject = new $builder($this->resource, $this->command->filesystem);

                        $output = $builderObject->process('__METHOD.'.$var.'__', $output);

                        break;
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Try and write output string to a file path.
     *
     * @param string $file
     * @param string $output
     * @return bool
     */
    protected function writeOutputFile($file, $output)
    {
        $return = $this->command->filesystem->put($file, $output);

        if ($return === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the subs for a given resouce type
     * ModelBuilder -> model.
     *
     * @return array
     */
    protected function stubs()
    {
        $class = explode('\\', static::class);

        $class = strtolower(str_replace(['Builder', 'Cleaner'], '', end($class)));

        $config = config('maker.stubs-'.$class);

        return $config;
    }
}
