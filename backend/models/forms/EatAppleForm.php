<?php

namespace backend\models\forms;

use yii\base\Model;

class EatAppleForm extends Model {

    public ?int $id = null;
    public ?int $size = null;

    public function rules(): array {
        return [
          [['id', 'size'], 'required'],
          ['id', 'integer', 'min' => 1],
          ['size', 'integer', 'min' => 1 , 'max' => 100, 'message' => 'Неверно'],
        ];
    }

    public function attributeLabels(): array {
        return [
            'size' => 'Кол-во',
        ];
    }

    public function getId(): int {
        return $this->id;
    }

    public function getSize(): int {
        return $this->size;
    }
}