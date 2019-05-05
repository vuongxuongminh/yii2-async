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
        'async' => 'vxm\async\Async'
    ]
]
```

### Run async code

After add it to application components, now you can run an async code:

```php

Yii::$app->async->run(function() {
    
    sleep(30);
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

Sometime you need to wait a code executed, just call `await`:

```php

Yii::$app->async->run(function() {
    
    sleep(30);
})->wait(); // sleep 30s

```

Or you can wait multi tasks executed:

```php

Yii::$app->async->run(function() {
    
    sleep(30);
});

Yii::$app->async->run(function() {
    
    sleep(100);
});

Yii::$app->async->wait(); // sleep 100s not 130s because it's run on multi processes

```

### Working with task

Besides using closures, you can also work with a Task. A Task is useful in situations where you need more setup work in the child process. 
Because a child process is always bootstrapped from nothing, chances are you'll want to initialise eg. the application components before executing the task. 
The Task class makes this easier to do.

```php

use vxm\async\Task;

class MyTask extends Task
{
    public function configure()
    {
        // Setup eg. load config, properties, components...
    }

    public function run()
    {
        // Do the real work here.
    }
}

// Do task async

Yii::$app->async->run(new MyTask);

```
