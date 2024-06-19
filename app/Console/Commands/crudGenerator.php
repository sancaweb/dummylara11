<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class crudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Simple Crud';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $this->controller($name);
        $this->model($name);   //create api route
    //     File::append(
    //         base_path('routes/api.php'),
    //         "Route::post('" . Str::plural(strtolower($name)) . "/create', '{$name}Controller@create');
    //   Route::post('" . Str::plural(strtolower($name)) . "/show', '{$name}Controller@show');
    //   Route::post('" . Str::plural(strtolower($name)) . "/update/{id}', '{$name}Controller@update');
    //   Route::delete('" . Str::plural(strtolower($name)) . "/delete/{id}', '{$name}Controller@delete');"
    //     );
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }


    protected function controller($name)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{modelNameSingular}}'

            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name)
            ],
            $this->getStub('Controller')
        );
        file_put_contents(app_path("/Http/Controllers/{$name}Controller.php"), $controllerTemplate);
    }

    protected function model($name)
    {
        $columns = ['id','name','address','phone'];
        $columnsString = implode("', '", $columns);

        $foreignClass = 'Products';
        $relationBelongs = 'public function '.$foreignClass.'()
        {
            $this->belongsTo('.$foreignClass.'::class);
        }';

        $modelTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePlural}}',
                '{{columns}}',
                '{{relationBelongs}}'
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                $columnsString,
                $relationBelongs

            ],
            $this->getStub('Model')
        );
        file_put_contents(app_path("Models/{$name}.php"), $modelTemplate);
    }
}
