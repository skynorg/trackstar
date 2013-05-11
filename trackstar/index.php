<?php

$somevar1 = defined('YII_DEBUG');

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/YiiBase.php';
$config=dirname(__FILE__).'/protected/config/main.php';


require_once($yii);

class Yii extends YiiBase {
    /**
     * @static
     * @return CWebApplication
     */
    public static function app()
    {
        return parent::app();
    }
}




$app = Yii::createWebApplication($config);
Yii::app()->onBeginRequest  = function($event) {
    return ob_start('ob_gzhandler');
};
Yii::app()->onEndRequest = function($event) {
    return ob_end_flush();
};
$app->run();