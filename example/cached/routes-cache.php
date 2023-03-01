<?php declare(strict_types=1);
/*
 * This is routes cache file of the package `inhere/sroute`.
 * It is auto generate by Guirong\PhpRouter\CachedRouter.
 * @count 44
 * @notice Please don't edit it.
 */
return [
// static routes
    'staticRoutes' => [
        'GET /routes' => [
            'path' => '/routes',
            'method' => 'GET',
            'handler' => 'dump_routes',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'GET /*' => [
            'path' => '/*',
            'method' => 'GET',
            'handler' => 'main_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'POST /*' => [
            'path' => '/*',
            'method' => 'POST',
            'handler' => 'main_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'PUT /*' => [
            'path' => '/*',
            'method' => 'PUT',
            'handler' => 'main_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'PATCH /*' => [
            'path' => '/*',
            'method' => 'PATCH',
            'handler' => 'main_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'DELETE /*' => [
            'path' => '/*',
            'method' => 'DELETE',
            'handler' => 'main_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'OPTIONS /*' => [
            'path' => '/*',
            'method' => 'OPTIONS',
            'handler' => 'main_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'HEAD /*' => [
            'path' => '/*',
            'method' => 'HEAD',
            'handler' => 'main_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'CONNECT /*' => [
            'path' => '/*',
            'method' => 'CONNECT',
            'handler' => 'main_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'GET /' => [
            'path' => '/',
            'method' => 'GET',
            'handler' => 'handler0',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'GET /home' => [
            'path' => '/home',
            'method' => 'GET',
            'handler' => 'Guirong\\Route\\Example\\Controllers\\HomeController@index',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'POST /post' => [
            'path' => '/post',
            'method' => 'POST',
            'handler' => 'post_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'PUT /put' => [
            'path' => '/put',
            'method' => 'PUT',
            'handler' => 'main_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'DELETE /del' => [
            'path' => '/del',
            'method' => 'DELETE',
            'handler' => 'main_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'POST /pd' => [
            'path' => '/pd',
            'method' => 'POST',
            'handler' => 'multi_method_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'DELETE /pd' => [
            'path' => '/pd',
            'method' => 'DELETE',
            'handler' => 'multi_method_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'GET /user/login' => [
            'path' => '/user/login',
            'method' => 'GET',
            'handler' => 'default_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
        'POST /user/login' => [
            'path' => '/user/login',
            'method' => 'POST',
            'handler' => 'default_handler',
            'bindVars' => [],
            'params' => [],
            'pathVars' => [],
            'pathRegex' => '',
            'pathStart' => '',
            'chains' => [],
            'options' => [],
        ],
    ],
// regular routes
    'regularRoutes' => [
        'GET 50be3774f6' => [
            0 => [
                'path' => '/50be3774f6/{arg1}/{arg2}/{arg3}/{arg4}/{arg5}/{arg6}/{arg7}/{arg8}/{arg9}/850726135a',
                'method' => 'GET',
                'handler' => 'handler0',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'arg1',
                    1 => 'arg2',
                    2 => 'arg3',
                    3 => 'arg4',
                    4 => 'arg5',
                    5 => 'arg6',
                    6 => 'arg7',
                    7 => 'arg8',
                    8 => 'arg9',
                ],
                'pathRegex' => '#^/50be3774f6/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/850726135a$#',
                'pathStart' => '/50be3774f6/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'GET user' => [
            0 => [
                'path' => '/user/{id}/followers',
                'method' => 'GET',
                'handler' => 'main_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'id',
                ],
                'pathRegex' => '#^/user/([^/]+)/followers$#',
                'pathStart' => '/user/',
                'chains' => [],
                'options' => [],
            ],
            1 => [
                'path' => '/user/detail/{id}',
                'method' => 'GET',
                'handler' => 'main_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'id',
                ],
                'pathRegex' => '#^/user/detail/([^/]+)$#',
                'pathStart' => '/user/detail/',
                'chains' => [],
                'options' => [],
            ],
            2 => [
                'path' => '/user/{id}',
                'method' => 'GET',
                'handler' => 'main_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'id',
                ],
                'pathRegex' => '#^/user/([^/]+)$#',
                'pathStart' => '/user/',
                'chains' => [],
                'options' => [],
            ],
            3 => [
                'path' => '/user/{some}',
                'method' => 'GET',
                'handler' => 'default_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'some',
                ],
                'pathRegex' => '#^/user/([^/]+)$#',
                'pathStart' => '/user/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'PUT user' => [
            0 => [
                'path' => '/user/detail/{id}',
                'method' => 'PUT',
                'handler' => 'main_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'id',
                ],
                'pathRegex' => '#^/user/detail/([^/]+)$#',
                'pathStart' => '/user/detail/',
                'chains' => [],
                'options' => [],
            ],
            1 => [
                'path' => '/user/{id}',
                'method' => 'PUT',
                'handler' => 'main_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'id',
                ],
                'pathRegex' => '#^/user/([^/]+)$#',
                'pathStart' => '/user/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'POST user' => [
            0 => [
                'path' => '/user/{id}',
                'method' => 'POST',
                'handler' => 'main_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'id',
                ],
                'pathRegex' => '#^/user/([^/]+)$#',
                'pathStart' => '/user/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'DELETE user' => [
            0 => [
                'path' => '/user/{id}',
                'method' => 'DELETE',
                'handler' => 'main_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'id',
                ],
                'pathRegex' => '#^/user/([^/]+)$#',
                'pathStart' => '/user/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'DELETE del' => [
            0 => [
                'path' => '/del/{uid}',
                'method' => 'DELETE',
                'handler' => 'main_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'uid',
                ],
                'pathRegex' => '#^/del/([^/]+)$#',
                'pathStart' => '/del/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'GET admin' => [
            0 => [
                'path' => '/admin/manage/getInfo[/id/{int}]',
                'method' => 'GET',
                'handler' => 'default_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'int',
                ],
                'pathRegex' => '#^/admin/manage/getInfo(?:/id/(\\d+))?$#',
                'pathStart' => '/admin/manage/getInfo',
                'chains' => [],
                'options' => [],
            ],
        ],
        'POST admin' => [
            0 => [
                'path' => '/admin/manage/getInfo[/id/{int}]',
                'method' => 'POST',
                'handler' => 'default_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'int',
                ],
                'pathRegex' => '#^/admin/manage/getInfo(?:/id/(\\d+))?$#',
                'pathStart' => '/admin/manage/getInfo',
                'chains' => [],
                'options' => [],
            ],
        ],
        'GET home' => [
            0 => [
                'path' => '/home/{act}',
                'method' => 'GET',
                'handler' => 'Guirong\\Route\\Example\\Controllers\\HomeController',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'act',
                ],
                'pathRegex' => '#^/home/([a-zA-Z][\\w-]+)$#',
                'pathStart' => '/home/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'POST home' => [
            0 => [
                'path' => '/home/{act}',
                'method' => 'POST',
                'handler' => 'Guirong\\Route\\Example\\Controllers\\HomeController',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'act',
                ],
                'pathRegex' => '#^/home/([a-zA-Z][\\w-]+)$#',
                'pathStart' => '/home/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'PUT home' => [
            0 => [
                'path' => '/home/{act}',
                'method' => 'PUT',
                'handler' => 'Guirong\\Route\\Example\\Controllers\\HomeController',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'act',
                ],
                'pathRegex' => '#^/home/([a-zA-Z][\\w-]+)$#',
                'pathStart' => '/home/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'PATCH home' => [
            0 => [
                'path' => '/home/{act}',
                'method' => 'PATCH',
                'handler' => 'Guirong\\Route\\Example\\Controllers\\HomeController',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'act',
                ],
                'pathRegex' => '#^/home/([a-zA-Z][\\w-]+)$#',
                'pathStart' => '/home/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'DELETE home' => [
            0 => [
                'path' => '/home/{act}',
                'method' => 'DELETE',
                'handler' => 'Guirong\\Route\\Example\\Controllers\\HomeController',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'act',
                ],
                'pathRegex' => '#^/home/([a-zA-Z][\\w-]+)$#',
                'pathStart' => '/home/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'OPTIONS home' => [
            0 => [
                'path' => '/home/{act}',
                'method' => 'OPTIONS',
                'handler' => 'Guirong\\Route\\Example\\Controllers\\HomeController',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'act',
                ],
                'pathRegex' => '#^/home/([a-zA-Z][\\w-]+)$#',
                'pathStart' => '/home/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'HEAD home' => [
            0 => [
                'path' => '/home/{act}',
                'method' => 'HEAD',
                'handler' => 'Guirong\\Route\\Example\\Controllers\\HomeController',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'act',
                ],
                'pathRegex' => '#^/home/([a-zA-Z][\\w-]+)$#',
                'pathStart' => '/home/',
                'chains' => [],
                'options' => [],
            ],
        ],
        'CONNECT home' => [
            0 => [
                'path' => '/home/{act}',
                'method' => 'CONNECT',
                'handler' => 'Guirong\\Route\\Example\\Controllers\\HomeController',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'act',
                ],
                'pathRegex' => '#^/home/([a-zA-Z][\\w-]+)$#',
                'pathStart' => '/home/',
                'chains' => [],
                'options' => [],
            ],
        ],
    ],
// vague routes
    'vagueRoutes' => [
        'GET' => [
            0 => [
                'path' => '/{name}',
                'method' => 'GET',
                'handler' => 'default_handler',
                'bindVars' => [
                    'name' => 'blog|saying',
                ],
                'params' => [],
                'pathVars' => [
                    0 => 'name',
                ],
                'pathRegex' => '#^/(blog|saying)$#',
                'pathStart' => '',
                'chains' => [],
                'options' => [],
            ],
            1 => [
                'path' => '/about[.html]',
                'method' => 'GET',
                'handler' => 'Guirong\\Route\\Example\\Controllers\\HomeController@about',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [],
                'pathRegex' => '#^/about(?:\\.html)?$#',
                'pathStart' => '/about',
                'chains' => [],
                'options' => [],
            ],
            2 => [
                'path' => '/test[/optional]',
                'method' => 'GET',
                'handler' => 'default_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [],
                'pathRegex' => '#^/test(?:/optional)?$#',
                'pathStart' => '/test',
                'chains' => [],
                'options' => [],
            ],
            3 => [
                'path' => '/blog-{post}',
                'method' => 'GET',
                'handler' => 'default_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [
                    0 => 'post',
                ],
                'pathRegex' => '#^/blog-([^/]+)$#',
                'pathStart' => '/blog-',
                'chains' => [],
                'options' => [],
            ],
            4 => [
                'path' => '/blog[/index]',
                'method' => 'GET',
                'handler' => 'default_handler',
                'bindVars' => [],
                'params' => [],
                'pathVars' => [],
                'pathRegex' => '#^/blog(?:/index)?$#',
                'pathStart' => '/blog',
                'chains' => [],
                'options' => [],
            ],
            5 => [
                'path' => '/my[/{name}[/{age}]]',
                'method' => 'GET',
                'handler' => 'my_handler',
                'bindVars' => [
                    'age' => '\\d+',
                ],
                'params' => [],
                'pathVars' => [
                    0 => 'name',
                    1 => 'age',
                ],
                'pathRegex' => '#^/my(?:/([^/]+)(?:/(\\d+))?)?$#',
                'pathStart' => '/my',
                'chains' => [],
                'options' => [
                    'defaults' => [
                        'name' => 'God',
                        'age' => 25,
                    ],
                ],
            ],
        ],
    ],
];
