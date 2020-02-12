<?php

namespace dsj\rbac;

use yii\base\Module;

class RbacModule extends Module
{
    public $controllerNamespace = 'dsj\rbac\controllers';
    public $defaultRoute = 'index';
}