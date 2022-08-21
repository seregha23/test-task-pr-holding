<?php

namespace backend\models\forms;

use yii\base\Model;

class RemoveAppleForm extends Model {

    public int $id;

    public function rules(): array {
        return [
            ['id', 'required'],
            ['id', 'integer' , 'min' => 1]
        ];
    }

    public function getId(): int {
        return $this->id;
    }
}