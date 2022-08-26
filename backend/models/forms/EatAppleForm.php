<?php

namespace backend\models\forms;

use common\models\Apple;
use yii\base\Model;

class EatAppleForm extends Model {
    /** @var int */
    public $appleId;

    /** @var float $size */
    public $size;

    public ?Apple $apple = null;

    public function rules(): array {
        return [
            ['appleId', 'required'],
            ['appleId', 'checkAppleExistence'],
            ['size', 'number', 'min' => 0 , 'max' => 100, 'message' => 'Неверный размер куска'],
        ];
    }

    public function checkAppleExistence() {
        $this->apple = Apple::findOne($this->appleId);

        if (!$this->apple) {
            $this->addError('appleId', 'Яблоко не существует');
        }
    }
}