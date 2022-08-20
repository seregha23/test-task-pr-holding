<?php

namespace backend\models\forms;


use yii\base\Model;

class FallAppleForm extends Model {

    public int $id;

    public function rules(): array {
        return [
            ['id', 'integer' , 'min' => 1]
        ];
    }

    public function getId(): int {
        return $this->id;
    }
}