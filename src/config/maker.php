<?php

/*
 |--------------------------------------------------------------------------
 | Overview
 |--------------------------------------------------------------------------
 |
 | This is the config for the makers package.
 |
 | Altering this config will allow you to use custom stubs, templates and field builders
 |
 */

return [

    /*
     |--------------------------------------------------------------------------
     | Pagination limit
     |--------------------------------------------------------------------------
     */
    'limit' => '10',

    /*
     |--------------------------------------------------------------------------
     | Definitions directory
     |--------------------------------------------------------------------------
     |
     | The directory that holds the yaml or json definition files
     | Relative to /
     |
     */
    'definitions-directory' => 'database/definitions/',

    /*
     |--------------------------------------------------------------------------
     | Controller stubs
     |--------------------------------------------------------------------------
     |
     | The default paths for the controller stubs
     | You can override these to use your own stubs
     |
     | source -> destination
     |
     */
    'stubs-controller' => [

        'app/Http/Controllers/controller.stub' => 'app/Http/Controllers/{controller_name}Controller.php',
        'app/Http/Controllers/Admin/controller.stub' => 'app/Http/Controllers/{controller_namespace}/{controller_name}{controller_namespace}Controller.php',

    ],

    /*
     |--------------------------------------------------------------------------
     | Model stubs
     |--------------------------------------------------------------------------
     |
     | The default paths for the model stubs
     | You can override these to use your own stubs
     |
     | source -> destination
     |
     */
    'stubs-model' => [

        'app/Models/model.stub' => 'app/Models/{model_name}.php',

    ],

    /*
     |--------------------------------------------------------------------------
     | Request stubs
     |--------------------------------------------------------------------------
     |
     | The default paths for the request stubs
     | You can override these to use your own stubs
     |
     | source -> destination
     |
     */
    'stubs-request' => [

        'app/Http/Requests/store_request.stub' => 'app/Http/Requests/Store{model_name}Request.php',
        'app/Http/Requests/update_request.stub' => 'app/Http/Requests/Update{model_name}Request.php',

    ],

    /*
     |--------------------------------------------------------------------------
     | Route stubs
     |--------------------------------------------------------------------------
     |
     | The route stub
     | You can override these to use your own stubs
     |
     | source -> destination
     |
     */
    'stubs-route' => [

        'routes/web.stub' => 'routes/web.php',

    ],

    /*
     |--------------------------------------------------------------------------
     | Templates
     |--------------------------------------------------------------------------
     |
     | The default paths for the templates
     | Templates are basically the same as stubs but don't have any placeholders
     |
     | You can override these to use your own templates (or stubs that can use placeholders too)
     |
     | source -> destination
     |
     */
    'stubs-template' => [

        'app/Models/BaseModel.stub' => 'app/Models/BaseModel.php',
        'app/Http/Controllers/BasePublicController.stub' => 'app/Http/Controllers/BasePublicController.php',
        'app/Http/Controllers/Admin/BaseAdminController.stub' => 'app/Http/Controllers/{controller_namespace}/BaseAdminController.php',
        'resources/views/admin/partials/search.blade.stub' => 'resources/views/{resource_view_namespace}/partials/search.blade.php',

    ],

    /*
     |--------------------------------------------------------------------------
     | View stubs
     |--------------------------------------------------------------------------
     |
     | The default paths for the view stubs
     | You can override these to use your own stubs
     |
     | source -> destination
     |
     */
    'stubs-view' => [

        'resources/views/index.blade.stub' => 'resources/views/{resource_view_directory}/index.blade.php',
        'resources/views/show.blade.stub' => 'resources/views/{resource_view_directory}/show.blade.php',
        'resources/views/admin/index.blade.stub' => 'resources/views/{resource_view_namespace}/{resource_view_directory}/index.blade.php',
        'resources/views/admin/show.blade.stub' => 'resources/views/{resource_view_namespace}/{resource_view_directory}/show.blade.php',
        'resources/views/admin/delete.blade.stub' => 'resources/views/{resource_view_namespace}/{resource_view_directory}/delete.blade.php',
        'resources/views/admin/create.blade.stub' => 'resources/views/{resource_view_namespace}/{resource_view_directory}/create.blade.php',
        'resources/views/admin/edit.blade.stub' => 'resources/views/{resource_view_namespace}/{resource_view_directory}/edit.blade.php',
        'resources/views/admin/partials/form.blade.stub' => 'resources/views/{resource_view_namespace}/{resource_view_directory}/partials/form.blade.php',
        'resources/views/admin/partials/edit-delete-buttons.blade.stub' => 'resources/views/{resource_view_namespace}/{resource_view_directory}/partials/edit-delete-buttons.blade.php',

    ],

    /*
     |--------------------------------------------------------------------------
     | The default fields listed in the view templates
     |--------------------------------------------------------------------------
     |
     | Control the default fields displayed in the view templates:
     |  - index
     |  - show
     |  - admin.index
     |  - admin.show
     |
     | These fields can be overridden and extended in the yaml defination files
     |
     */
    'stubs-view-fields' => [

        'index' => ['name', 'title'],
        'show' => ['name', 'title', 'content', 'created_at'],
        'admin.index' => ['id', 'name', 'title', 'live', 'active', 'published', 'created_at', 'updated_at'],
        'admin.show' => [], // show all
    ],

    /*
     |--------------------------------------------------------------------------
     | Field Builder
     |--------------------------------------------------------------------------
     |
     | Define the class used to generate the html for each field
     |
     */

    'field-builders' => [

        'AdminFormBuilder' => '\\AbCreative\\LaravelMaker\\Builders\\FieldBuilders\\AdminFormBuilder',
        'AdminIndexBuilder' => '\\AbCreative\\LaravelMaker\\Builders\\FieldBuilders\\AdminIndexBuilder',
        'AdminShowBuilder' => '\\AbCreative\\LaravelMaker\\Builders\\FieldBuilders\\AdminShowBuilder',
        'IndexBuilder' => '\\AbCreative\\LaravelMaker\\Builders\\FieldBuilders\\IndexBuilder',
        'ShowBuilder' => '\\AbCreative\\LaravelMaker\\Builders\\FieldBuilders\\ShowBuilder',
        'RouteBuilder' => '\\AbCreative\\LaravelMaker\\Builders\\FieldBuilders\\RouteFieldBuilder',
        'ModelRelationshipsBuilder' => '\\AbCreative\\LaravelMaker\\Builders\\FieldBuilders\\ModelRelationshipsBuilder',
        'RelationshipLookupQueriesBuilder' => '\\AbCreative\\LaravelMaker\\Builders\\FieldBuilders\\RelationshipLookupQueriesBuilder',
        'RelationshipLookupVarsBuilder' => '\\AbCreative\\LaravelMaker\\Builders\\FieldBuilders\\RelationshipLookupVarsBuilder',

    ],

];
