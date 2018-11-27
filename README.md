# Laravel Maker

## Overview

Laravel Maker is a tool to kick start project development and help with prototyping by creating scaffolding.

The goal of Laravel Maker is not to generate a production ready CMS but to simply take away some of the boring setup at the start of a project and create un-opinionated code that can easly be modified and extended. Laravel Maker is ideal for prototyping and experimenting - create definitions, generate code, try out, tweak, re-generate, repeat.

Laravel Maker is used to generate code that forms that basis for production projects, but it isn't stable yet and breaking changes do happen.

Define your Models in YAML (or generate YAML from existing database tables), then use artisan commands to generate Migrations, Models, Controllers, Views, Request classes etc.

The package can create a complete CRUD scaffolding or individual elements:

- Base controller
- Base admin controller
- Admin controller
- Public controller
- Update request
- Store request
- Base Model
- Model
- Repository (todo)
- Migration
- Model factory (todo)
- Routes
- Public views
- Admin views


## Requirements

Laravel >= 5.6 

## Example usage

1. Create a YAML file e.g. `notes.yaml` in `database/definitions`
2. Run `php artisan build:crud notes.yaml`

A definition can be as simple as:

```yaml

Note:
    model:
    fields:
        name:
            type: string
        slug:
            type: string
        content:
            type: text
        is_alive:
            type: boolean
            
``` 

This will create all the necessary files to create, edit, view, and delete notes and a migration file.




## Installation

### Setup a new Laravel site

Create a database and then setup a Laravel site as normal... e.g.

```bash

laravel new scaffolding.example.com
cd scaffolding.example.com
php artisan key:generate
nano .env
php artisan migrate
php artisan make:auth

```

Create an admin user (if necessary) e.g.

```
php artisan tinker
```
  
Then:
    
```

$user = new User;
$user->email = 'admin@example.com';
$user->name = 'Admin';
$user->password = Hash::make('123456');
$user->save();

```

    
### Edit composer.json

Add the repository, add the package to require-dev, and ensure minimum-stability set to dev

e.g.

```json

{
    "repositories" : [
        {
            "type" : "git",
            "url" : "https://github.com/johnsvenn/laravel-maker"
        }
    ],
    "require-dev" : {
        "ab-creative/laravel-maker" : "*"
    },
    "minimum-stability" : "dev"
}
```

**Note** The above packages should install automatically using [Package Auto-Discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518) but you may need to update Composer e.g. `composer self-update` then `php artisan package:discover`

You probably only want the package to run in development therefore edit `app\Providers\AppServiceProvider.php` and edit the register method. 

```php
public function register()
    {
        //
        
        if ($this->app->environment() == 'local') {
            
            $this->app->register('AbCreative\LaravelMaker\LaravelMakerServiceProvider');
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
            
        }
    }
```

Publish the `config/maker.php` and `example.yaml` using `php artisan vendor:publish`

To check if the installation has worked type `php artisan list` you should see the following commands:

```
 build
  build:clean            Delete all the files generated by the package
  build:controller       Create a new controller
  build:crud             Create a new model, controllers, routes, views and migration
  build:migration        Create a new set of migrations
  build:model            Create a new model
  build:request          Create a new set of request classes
  build:route            Create a new route
  build:view             Create a new set of view files
  build:yaml             Create Yaml from one or more database tables
```

You can then run `php artisan build:crud example.yaml` to build the example scaffolding and `php artisan migrate` to build the example tables.

You can then login to your site and goto `/admin/posts` or `/admin/books ` to see the scaffolding.

When you are finished, to delete your scaffolding run `php artisan build:clean example.yaml`

## Conventions

The package tries to follow Laravel conventions e.g.


- Post
- PostController
- PostAdminController
- UpdatePostRequest
- @foreach($posts as $post)
- views/posts/show.blade.php
- views/admin/posts/show.blade.php



## YAML

Laravel Maker reads YAML files. These files contain the information about your Model.

```yaml

Post: #The name of the 

  model:
    name: Post
    table: posts
    fields:
      name:
        label: Post name
        type: string, 100
        rules: required|unique:posts|max:100
        messages: Please add a post name
        placeholder: Name
      slug:
        type: string, 120
        rules: required|unique:posts|max:120
        validate:
          - required
      content:
        type: text
      is_alive:
        type: boolean
        label: Active?
        
```


## Artisan commands

`file` is a yaml file defined in `/database/definitions/`
`tables` a comma separated list of tables
**Options**
`--clean` will delete an existing file
`--force` will overwrite an existing file

Usage: `php artisan build:command file options`

`build:clean file` Delete all the files generated by the package

`build:controller file --force --clean` Create a new controller

`build:crud file --force --clean` Create a new model, controllers, routes, views and migration

`build:migration file --force --clean` Create a new set of migrations

`build:model file --force --clean` Create a new model

`build:request file --force --clean` Create a new set of request classes

`build:route file --force --clean` Create a new route

`build:view file --force --clean` Create a new set of view files

`build:yaml tables --force --clean` Create Yaml from one or more database tables




## Custom templates

The package contains stub files that are processed and which are then published in your application.

### Stubs

This directory mirrors the structure of Laravel and is setup to use Request and Repository classes. 
Stubs can be overloaded on a individual basis based on a paths defined in `config/maker.php`


## Tests


cd into `vendor/ab-creative/laravel-maker/` directory

run `../../bin/phpunit` 

  

## Copyright

For the full copyright and license information, please view the LICENSE file that was distributed with this source code.

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
