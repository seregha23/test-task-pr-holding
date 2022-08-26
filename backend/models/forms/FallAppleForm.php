<?php

namespace backend\models\forms;

use common\models\Apple;
use yii\base\Model;

class FallAppleForm extends Model {
    /** @var int|null $appleId */
    public $appleId = null;

    public ?Apple $apple = null;

    public function rules(): array {
        return [
            ['appleId', 'required'],
            ['appleId', 'checkAppleExistence']
        ];
    }

    public function checkAppleExistence() {
        $this->apple = Apple::findOne($this->appleId);

        if (!$this->apple) {
            $this->addError('appleId', 'Яблоко не существует');
        }
    }
}