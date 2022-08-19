<?php

namespace backend\controllers;

use common\helpers\AppleHelper;
use common\models\Apple;
use ErrorException;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class ApplesController extends Controller {

    /** @throws BadRequestHttpException */
    public function beforeAction($action): bool {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionIndex(): string {
        $apples = Apple::find()->all();
        return $this->render('//apples/index', ['apples' => $apples]);
    }

    public function actionFallToGround(int $id): string {
        AppleHelper::fallToGround($id);
        $apples = Apple::find()->all();
        return $this->render('//apples/index', ['apples' => $apples]);
    }

    /** @throws ErrorException */
    public function actionEatApple(int $id, int $size): string {
        AppleHelper::eat($id, $size);
        $apples = Apple::find()->all();
        return $this->render('//apples/index', ['apples' => $apples]);
    }

    public function actionGenerate(): string {
        $appleHelper = new AppleHelper();
        $appleHelper->generateRandom();
        $apples = Apple::find()->all();
        return $this->render('//apples/index', ['apples' => $apples]);

    }

    public function actionAjaxGenerateRandom(): array {
        $response = [
            'message' => 'Ошибка при генерации'
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (request()->isAjax) {
            $appleHelper = new AppleHelper();
            $appleHelper->generateRandom();
            $response = [
                'message' => 'Успешно сгенерировано'
            ];
        }
        return $response;
    }
}