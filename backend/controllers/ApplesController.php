<?php

namespace backend\controllers;

use backend\models\forms\EatAppleForm;
use backend\models\forms\FallAppleForm;
use backend\models\forms\RemoveAppleForm;
use common\helpers\ApplesProvider;
use common\models\Apple;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class ApplesController extends Controller {
    public function behaviors(): array {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'regenerate-random-apples', 'fall-to-ground', 'eat-apple', 'remove-apple'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'regenerate-random-apples' => ['post'],
                    'fall-to-ground' => ['post'],
                    'eat-apple' => ['post'],
                    'remove-Apple' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string {
        return $this->render('index', [
            'apples'          => Apple::find()->all(),
            'eatAppleForm'    => new EatAppleForm(),
            'fallAppleForm'   => new FallAppleForm(),
            'removeAppleForm' => new RemoveAppleForm()
        ]);
    }

    public function actionRegenerateRandomApples(): array|Response {
        $success = true;
        $message = 'Яблоки успешно сгенерированы';

        $appleHelper = new ApplesProvider();
        $appleHelper::regenerateRandomApples();

        if (request()->isAjax) {
            return $this->ajaxResponse($success, $message);
        } else {
            session()->setFlash('success', $message);
            return $this->redirect('index');
        }
    }

    public function actionFallToGround(): array|Response {
        $success = false;

        $fallAppleForm = new FallAppleForm();
        if ($fallAppleForm->load(request()->post()) && $fallAppleForm->validate()) {
            try {
                $fallAppleForm->apple->fallToGround();
                $success = true;
                $message = 'Яблоко успешно упало, а может и разбилось';
            } catch (Exception $e) {
                $message = $e->getMessage();
            }
        } else {
            $message = 'Ошибка валидации: ' . json_encode($fallAppleForm->errors, JSON_UNESCAPED_UNICODE);
        }

        if (request()->isAjax) {
            return $this->ajaxResponse($success, $message);
        } else {
            session()->setFlash($success ? 'success' : 'error', $message);
            return $this->redirect('index');
        }
    }

    public function actionEatApple(): array|Response {
        $success = false;

        $eatAppleForm = new EatAppleForm();
        if ($eatAppleForm->load(request()->post()) && $eatAppleForm->validate()) {
            try {
                $eatAppleForm->apple->eat($eatAppleForm->size);
                $success = true;
                $message = 'Яблоко успешно откушено';
            } catch (Exception $e) {
                $message = $e->getMessage();
            }
        } else {
            $message = 'Ошибка валидации: ' . json_encode($eatAppleForm->errors, JSON_UNESCAPED_UNICODE);
        }

        if (request()->isAjax) {
            return $this->ajaxResponse($success, $message);
        } else {
            session()->setFlash($success ? 'success' : 'error', $message);
            return $this->redirect('index');
        }
    }

    public function actionRemoveApple(): array|Response {
        $success = false;

        $removeAppleForm = new RemoveAppleForm();
        if ($removeAppleForm->load(request()->post()) && $removeAppleForm->validate()) {
            try {
                $removeAppleForm->apple->delete();
                $success = true;
                $message = 'Яблоко успешно удалено';
            } catch (Exception $e) {
                $message = $e->getMessage();
            }
        } else {
            $message = 'Ошибка валидации: ' . json_encode($removeAppleForm->errors, JSON_UNESCAPED_UNICODE);
        }

        if (request()->isAjax) {
            return $this->ajaxResponse($success, $message);
        } else {
            session()->setFlash($success ? 'success' : 'error', $message);
            return $this->redirect('index');
        }
    }

    protected function ajaxResponse(bool $success, string $message): array {
        response()->format = Response::FORMAT_JSON;

        return [
            'success' => $success,
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
