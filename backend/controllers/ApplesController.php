<?php

namespace backend\controllers;

use backend\models\forms\RemoveAppleForm;
use common\helpers\ApplesProvider;
use common\models\Apple;
use backend\models\forms\EatAppleForm;
use backend\models\forms\FallAppleForm;
use ErrorException;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class ApplesController extends Controller {

    public function actionIndex(): string {
//        $query  = Apple::find();
//        $pages  = new Pagination(['totalCount' => $query->count(),'pageSize' => 4]);
//        $apples = Apple::find()->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', [
            'apples'          => Apple::find()->all(),
            'eatAppleForm'    => new EatAppleForm(),
            'fallAppleForm'   => new FallAppleForm(),
            'removeAppleForm' => new RemoveAppleForm(),
            ]);
    }

    public function actionRegenerateRandomApples(): Response {
        $appleHelper = new ApplesProvider();
        $appleHelper::regenerateRandomApples();
        app()->session->setFlash('success', "Яблоки сгенерированы");
        return $this->redirect('index');
    }

    public function actionFallToGround(): Response {
        $fallAppleForm = new FallAppleForm();
        if ($fallAppleForm->load(request()->post()) && $fallAppleForm->validate()) {
            $idApple = $fallAppleForm->getId();
            $apple   = Apple::find()->andWhere(['id' => $idApple])->one();
            $apple->fallToGround();
            app()->session->setFlash('success', "Яблоко упало");
        }
        return $this->redirect('index');
    }

    public function actionEatApple(): Response {
        $eatAppleForm = new EatAppleForm();
        if ($eatAppleForm->load(request()->post()) && $eatAppleForm->validate()) {
            $idApple = $eatAppleForm->getId();
            $apple   = Apple::find()->andWhere(['id' => $idApple])->one();
            $apple->eat($eatAppleForm->getSize());
            app()->session->setFlash('success', "Яблоко откушено");
        }
        return $this->redirect('index');
    }

    public function actionRemoveApple(): Response {
        $removeAppleForm = new RemoveAppleForm();
        if ($removeAppleForm->load(request()->post()) && $removeAppleForm->validate()) {
            $idApple = $removeAppleForm->getId();
            $apple   = Apple::find()->andWhere(['id' => $idApple])->one();
            $apple->delete();
            app()->session->setFlash('success', "Яблоко удалено");
        }
        return $this->redirect('index');
    }


    public function actionAjaxRegenerateRandomApples(): array {
        $response = [
            'success' => false,
            'message' => 'Ошибка при генерации'
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (request()->isAjax) {
            $appleHelper = new ApplesProvider();
            $appleHelper::regenerateRandomApples();
            $response = $this->ajaxResponse('Успешно сгенерировано');
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
                $response = $this->ajaxResponse('Яблоко упало');
            }
        }
        return $response;
    }

    public function actionAjaxEatApple(): array {
        $response = [
            'success' => false,
            'message' => 'Ошибка при "откусить"'
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (request()->isAjax) {
            $eatAppleForm = new EatAppleForm();
            if ($eatAppleForm->load(request()->post()) && $eatAppleForm->validate()) {
                $idApple = $eatAppleForm->getId();
                $apple   = Apple::find()->andWhere(['id' => $idApple])->one();
                $apple->eat($eatAppleForm->getSize());
                $response = $this->ajaxResponse( 'Яблоко откушено');
            }
        }
        return $response;
    }

    /** @throws ErrorException */
    public function actionAjaxRemoveApple(): array {
        $response = [
            'success' => false,
            'message' => 'Ошибка при удалении'
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (request()->isAjax) {
            $removeAppleForm = new RemoveAppleForm();
            if ($removeAppleForm->load(request()->post()) && $removeAppleForm->validate()) {
                $idApple = $removeAppleForm->getId();
                $apple = Apple::find()->andWhere(['id' => $idApple])->one();
                $apple->delete();
                $response = $this->ajaxResponse('Яблоко удалено');
            }
        }
        return $response;
    }

    protected function ajaxResponse( string $message): array {
        return [
            'success' => true,
            'html' => [
                'cssClass' => '.apples__wrapper',
                'layout'   => $this->renderPartial('index', [
                    'apples'          => Apple::find()->all(),
                    'eatAppleForm'    => new EatAppleForm(),
                    'fallAppleForm'   => new FallAppleForm(),
                    'removeAppleForm' => new RemoveAppleForm(),
                ])
            ],
            'message' => $message,
        ];
    }
}