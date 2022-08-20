<?php

/** @var View $this */
/* @var $apple Apple */
/* @var $eatAppleForm EatAppleForm */

use common\models\Apple;
use backend\models\forms\EatAppleForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<div class="apples__item" data-id="<?= $apple->id ?>">
    <div class="apples__id"><?= $apple->id ?></div>
    <div class="apples__color"><?= $apple->color ?></div>
    <div class="apples__size"><?= $apple->size ?></div>
    <div class="apples__status"><?= $apple->status ?></div>
    <div class="apples__create-date"><?= $apple->create_at ?></div>
    <div class="apples__full-date"><?= $apple->fell_at ?></div>
    <div class="apples__button">
        <a id="fall-to-ground" data-id="<?= $apple->id ?>" href="javascript:void(0)" class="btn btn-primary <?= $apple->status_id == Apple::STATUS_ON_TREE ? '' : 'disabled'  ?>">Упасть</a>
    </div>
    <div class="apples__button">
        <?php $form = ActiveForm::begin([
                'options' => ['class' =>'eat-apple-form']
            ]); ?>
        <?= Html::activeHiddenInput($eatAppleForm, 'id', ['value' => $apple->id]) ?>
        <?= $form->field($eatAppleForm, 'size')->textInput(['autofocus' => true]) ?>
        <?= Html::submitButton('Откусить', ['class' => 'btn btn-primary btn-block', 'name' => 'eat-apple-button']) ?>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="apples__button">
        <a data-id="<?= $apple->id ?>" href="javascript:void(0)" class="btn btn-danger remove-apple">Удалить</a>
    </div>
</div>

