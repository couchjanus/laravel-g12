<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

// Константа LARAVEL_START сохраняет метку времени в миллисекундах при старте приложения и используется при отладке для определения времени, которое прошло до момента замера. 

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

// $user = new \App\User();

require __DIR__.'/../vendor/autoload.php';

// $user = new \App\User();

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';


// из объекта приложения ($app) можно получить доступ ко всем сервисам, которые регистрируются в сервис-контейнере
// dd(app('router'));


/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

// Затем привязываются некоторые важные интерфейсы в контейнере, чтобы мы могли разрешать их при необходимости. Ядра обслуживают входящие запросы к этому приложению как из Интернета, так и из CLI. В bootstrap/app.php регистрируются сервисы (интерфейсы и классы их реализующие). 

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// вызывается метод handle() класса Illuminate\Foundation\Http\Kernel из файла vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php, который занимается обработкой входящего запроса:

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// dd($request);

// Получив приложение, мы можем обработать входящий запрос через ядро и отправить соответствующий ответ обратно в браузер клиента, что позволит им насладиться креативным и замечательным приложением, которое мы для него подготовили.

// dd($response);

$response->send(); // отправляет HTTP-заголовки и контент.


$kernel->terminate($request, $response); // завершает работу приложения. 

// Выполняются одноименные методы у зарегистрированных посредников, например у lluminate\Session\Middleware\StartSession или у пользовательских, если переопределите в своем посреднике метод terminate($request, $response).
// Также этот метод вызывает зарегистрированные в свойстве приложения $terminatingCallbacks функции обратного вызова. Можно создать и привязать свои функции обратного вызова, чтобы они выполнялись после отправки ответа браузеру. 


// проверим сколько времени выполняется приложение на сервере
$time_end = microtime(true);
$time = $time_end - LARAVEL_START;
echo "Выполнено за $time секунд";


