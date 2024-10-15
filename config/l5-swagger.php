<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Documentation
    |--------------------------------------------------------------------------
    |
    | Here you can specify which documentation to use by default.
    |
    */

    'default' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Documentations
    |--------------------------------------------------------------------------
    |
    | Define multiple documentations if needed. Here we define 'default'.
    |
    */

    'documentations' => [
        'default' => [
            /*
             * Path to your YAML OpenAPI specification file.
             * Ensure that 'use_annotations' is set to false.
             */
            'generate_always' => false, // Disable automatic generation from annotations

            'paths' => [
                /*
                 * Disable annotation scanning since we're using a YAML file.
                 */
                'use_annotations' => false,

                /*
                 * Path to the YAML specification file.
                 * Make sure the path is correct relative to the project root.
                 */
                'docs_yaml' => base_path('resources/docs/swagger.yaml'),

                /*
                 * JSON documentation file (optional if you prefer YAML).
                 * This can be used if you want to serve JSON as well.
                 */
                'docs_json' => 'api-docs.json',

                /*
                 * Annotations paths are not used since 'use_annotations' is false.
                 * You can leave this empty or remove it if not needed.
                 */
                'annotations' => [
                    // app_path('Http/Controllers'),
                ],
            ],

            /*
             * Routes for accessing the Swagger UI and the API docs.
             */
            'routes' => [
                /*
                 * Route for accessing the Swagger UI.
                 * Example: http://your-app-url/api/documentation
                 */
                'docs' => 'api/documentation',

                /*
                 * Route for OAuth2 callback (if applicable).
                 */
                'oauth2_callback' => 'api/oauth2-callback',

                /*
                 * Route for serving the JSON documentation (optional).
                 * Example: http://your-app-url/api/docs.json
                 */
                'api' => 'api/docs.json',
            ],

            /*
             * Configuration for the Swagger UI.
             */
            'swagger_ui' => [
                'display' => [
                    'docExpansion' => 'none',       // Options: 'list', 'full', 'none'
                    'operationsSorter' => 'alpha',  // Options: 'alpha', 'method', 'none'
                ],
            ],

            /*
             * Security definitions if needed.
             */
            'security' => [
                /*
                 * Example API Key security scheme.
                 */
                'apiKey' => [
                    'type' => 'apiKey',
                    'description' => 'API key needed to access the endpoints',
                    'name' => 'Authorization',
                    'in' => 'header',
                ],
            ],

            /*
             * Additional configurations can be added here.
             */
            'constants' => [
                // Define any constants if necessary
            ],

            'paths' => [
                'docs' => storage_path('api-docs'), // Storage path for generated docs

                'docs_json' => 'api-docs.json',      // JSON docs filename

                'docs_yaml' => 'api-docs.yaml',      // YAML docs filename

                'annotations' => [
                    // Not used since 'use_annotations' is false
                ],

                'views' => base_path('resources/views/vendor/l5-swagger'), // Views path
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Swagger Version
    |--------------------------------------------------------------------------
    |
    | Specify the Swagger/OpenAPI version.
    |
    */

    'swagger_version' => '3.0',

    /*
    |--------------------------------------------------------------------------
    | Generate Always
    |--------------------------------------------------------------------------
    |
    | Whether to always generate the documentation on each request.
    | Set to false since we're using a static YAML file.
    |
    */

    'generate_always' => false,

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | Define global routes settings here if needed.
    |
    */

    'routes' => [
        /*
         * Set to false if you want to disable certain routes.
         */
        'docs' => true,
        'oauth2_callback' => true,
        'api' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | Define various paths used by L5-Swagger.
    |
    */

    'paths' => [
        /*
         * Path where the generated documentation files will be stored.
         */
        'docs' => storage_path('api-docs'),

        /*
         * Filename for the generated JSON documentation.
         */
        'docs_json' => 'api-docs.json',

        /*
         * Filename for the generated YAML documentation.
         */
        'docs_yaml' => 'api-docs.yaml',

        /*
         * Path(s) to scan for annotations (not used here).
         */
        'annotations' => [
            // app_path('Http/Controllers'),
        ],

        /*
         * Path to the views.
         */
        'views' => base_path('resources/views/vendor/l5-swagger'),

        /*
         * Base path for the Swagger UI (optional).
         */
        'base' => env('L5_SWAGGER_BASE_PATH', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Generate Options
    |--------------------------------------------------------------------------
    |
    | Additional options for the generator.
    |
    */

    'generate_options' => [
        /*
         * Example option: format the output.
         */
        'format' => 'yaml', // Options: 'json', 'yaml'
    ],

    /*
    |--------------------------------------------------------------------------
    | Additional Configuration
    |--------------------------------------------------------------------------
    |
    | You can add more configurations as needed.
    |
    */

    'additional_config_url' => null, // URL to additional config file if needed

];
