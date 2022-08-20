<?php

namespace backend\controllers;

use common\helpers\ApplesProvider;
use common\models\Apple;
use backend\models\forms\EatAppleForm;
use backend\models\forms\FallAppleForm;
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
        $apples       = Apple::find()->all();
        $eatAppleForm = new EatAppleForm();

        return $this->render('//apples/index', [
            'apples'       => $apples,
            'eatAppleForm' => $eatAppleForm,
            ]);
    }

    public function actionFallToGround(int $id): Response {
        $apple = Apple::find()->andWhere(['id' => $id])->one();
        $apple->fallToGround();
        return $this->redirect('//apples/index');
    }

    public function actionRegenerateRandomApples(): Response {
        $appleHelper = new ApplesProvider();
        $appleHelper::regenerateRandomApples();
        return $this->redirect('//apples/index');
    }

    public function actionAjaxRegenerateRandomApples(): array {
        $response = [
            'message' => 'Ошибка при генерации'
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (request()->isAjax) {
            $appleHelper = new ApplesProvider();
            $appleHelper::regenerateRandomApples();
            $response = [
                'html' => $this->renderPartial('index',  [
                    'apples'       => Apple::find()->all(),
                    'eatAppleForm' => new EatAppleForm(),
                ]),
                'message' => 'Успешно сгенерировано'
            ];
        }
        return $response;
    }

    public function actionAjaxFallToGround(): array {
        $response = [
            'message' => 'Ошибка при клике'
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (request()->isAjax) {
            $fallAppleForm = new FallAppleForm();
            if ($fallAppleForm->load(request()->post()) && $fallAppleForm->validate()) {
                $apple = Apple::find()->andWhere(['id' => $fallAppleForm->getId()])->one();
                $apple->fallToGround();
                $response = [
                    'message' => 'Яблоко упало'
                ];
            }
        }
        return $response;
    }

    public function actionAjaxEatApple(): array {
        $response = [
            'message' => 'Ошибка при "откусить"'
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (request()->isAjax) {
            $eatAppleForm = new EatAppleForm();
            if ($eatAppleForm->load(request()->post()) && $eatAppleForm->validate()) {
                $idApple = $eatAppleForm->getId();
                $apple   = Apple::find()->andWhere(['id' => $idApple])->one();
                $apple->eat($eatAppleForm->getSize());
                $response = [
                    'id'   => $idApple,
                    'html' => $this->renderPartial('_apple', [
                        'apples'       => Apple::find()->all(),
                        'eatAppleForm' => $eatAppleForm,
                    ]),
                    'message' => 'Яблоко откушено'
                ];
            }
        }
        return $response;
    }

    /** @throws ErrorException */
    public function actionAjaxRemoveApple(): array {
        $response = [
            'message' => 'Ошибка при удалении'
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (request()->isAjax) {
            $id = (int) request()->post('id');
            $apple = Apple::find()->andWhere(['id' => $id])->one();
            $apple->delete();
            $response = [
                'id'      => $id,
                'message' => 'Яблоко удалено'
            ];
        }
        return $response;
    }
}