<?php

use yii\helpers\Url;
use yii\base\Application;
use yii\web\Request;
use yii\web\Response;

//url
function url($params, $scheme = false): string {
    return Url::to($params, $scheme);
}

//request
function request(): Request {
    return Yii::$app->request;
}

//response
function response(): Response {
    return Yii::$app->response;
}

//app
function app(): Application {
    return Yii::$app;
}
