# Yii2 Async

[![Latest Stable Version](https://poser.pugx.org/vxm/yii2-async/v/stable)](https://packagist.org/packages/vxm/yii2-async)
[![Total Downloads](https://poser.pugx.org/vxm/yii2-async/downloads)](https://packagist.org/packages/vxm/yii2-async)
[![Build Status](https://travis-ci.org/vuongxuongminh/yii2-async.svg?branch=master)](https://travis-ci.org/vuongxuongminh/yii2-async)
[![Code Coverage](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-async/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-async/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-async/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-async/?branch=master)
[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)

## About it

An extension provide an easy way to run code asynchronous and parallel base on [spatie/async](https://github.com/spatie/async) wrapper for Yii2 application.

## Requirements

* [PHP >= 7.1](http://php.net)
* [yiisoft/yii2 >= 2.0.13](https://github.com/yiisoft/yii2)

## Installation

Require Yii2 Async using [Composer](https://getcomposer.org):

```bash
composer require vxm/yii2-async
```

## Usage

### Configure

Add the component to your application configure file:

```php
[
    'components' => [
        'async' => [
            'class' => 'vxm\async\Async',
            'appConfigFile' => '@app/config/async.php' // optional when you need to use yii feature in async process.
        ]
    ]
]
```

Because async code run in difference process you need to setup yii environment to use 
components via property `appConfigFile`. Example of an async app config file:

```php
define('YII_ENV', 'dev');
define('YII_DEBUG', true);

return [
    'id' => 'async-app',
    'basePath' => __DIR__,
    'runtimePath' => __DIR__ . '/runtime',
    'aliases' => [
        '@frontend' => dirname(__DIR__, 2) . '/frontend',
        '@backend' => dirname(__DIR__, 2) . '/backend'
    ]
];
```

Make sure all of your aliases define in it to support an autoload.

### Run async code

After add it to application components, now you can run an async code:

```php

Yii::$app->async->run(function() {
    
    Yii::$app->mailer->compose('mail')->send();
});

```

### Async events

When creating asynchronous processes, you can add the following event hooks on a process in the second parameter.

```php

Yii::$app->async->run(function() {

    if (rand(1, 2) === 1) {
    
        throw new \YourException;
    }
    
    return 123;
}, [
    'success' => function ($result) {
    
        echo $result; // 123
        
    },
    'catch' => function (\YourException $exception) {
        
        // catch only \YourException
        
    },
    'error' => function() {
    
        // catch all exceptions
        
    },
    'timeout' => function() {
    
        // call when task timeout default's 15s
        
    }
]);

```

### Wait process

Sometime you need to wait a code executed, just call `wait()` after `run()`:

```php

Yii::$app->async->run(function() {
    
    sleep(10);
})->wait(); // sleep 10s

```

Or you can wait multi tasks executed:

```php

Yii::$app->async->run(function() {
    
    sleep(5);
});

Yii::$app->async->run(function() {
    
    sleep(10);
});

Yii::$app->async->wait(); // sleep 10s not 15s because it's run on multi processes

```

### Working with task

Besides using closures, you can also work with a Task. A Task is useful in situations where you need more setup work in the child process. 

The Task class makes this easier to do.

```php

use vxm\async\Task;

class MyTask extends Task
{

    public $productId;
    

    public function run()
    {
        // Do the real work here.
       
    }
}

// Do task async use like an anonymous above.

Yii::$app->async->run(new MyTask([
    'productId' => 123

]));

```
