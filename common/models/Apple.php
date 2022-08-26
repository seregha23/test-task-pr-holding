<?php

namespace common\models;

use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 *
 * @property int $id [int(11)]
 * @property int $color_id [int(10)]
 * @property int $status_id [int(10)]
 * @property float $size [decimal(6,2)]
 * @property string $created_at [timestamp]
 * @property string $fell_at [timestamp]
 *
 * @property-read string $status
 * @property-read string $color
 */
class Apple extends ActiveRecord {
    const COLOR_GREEN = 1;
    const COLOR_RED   = 2;
    const COLOR_MULTI = 3;

    public static array $colors = [
        self::COLOR_GREEN => 'зеленый',
        self::COLOR_RED   => 'красный',
        self::COLOR_MULTI => 'зелено-красный',
    ];

    const STATUS_ON_TREE      = 1;
    const STATUS_ON_GROUND    = 2;
    const STATUS_ROTTEN_APPLE = 3;

    public static array $statuses = [
        self::STATUS_ON_TREE      => 'на дереве',
        self::STATUS_ON_GROUND    => 'на земле',
        self::STATUS_ROTTEN_APPLE => 'гнилое яблоко',
    ];

    public static function tableName(): string {
        return '{{%apples}}';
    }

    public function behaviors():array {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getColor(): string {
        return self::$colors[$this->color_id] ?? 'N/A';
    }

    public function getStatus(): string {
        return self::$statuses[$this->status_id] ?? 'N/A';
    }

    /** @throws Exception */
    public function fallToGround(): void {
        if ($this->status_id !== self::STATUS_ON_TREE) {
            throw new Exception('Только яблоко, висящее на дереве, может упасть');
        }

        $this->status_id = self::STATUS_ON_GROUND;
        $this->fell_at   = date('Y-m-d H:i:s', time());
        $this->save();
    }

    /** @throws Exception */
    public function eat(float $size): void {
        if ($this->status_id !== self::STATUS_ON_GROUND) {
            throw new Exception('Съесть можно только яблоко, лежащее на земля');
        }

        if ($size < 0 || $size > $this->size) {
            throw new Exception('Нельзя откусить такой кусок от яблока');
        }

        $this->size -= $size;
        $this->save();

        if ($this->size <= 0) {
            $this->delete();
        }
    }

    public function afterFind(): void {
        if ($this->fell_at && (time() - strtotime($this->fell_at)) > 5 * 60 * 60) {
            $this->status_id = self::STATUS_ROTTEN_APPLE;
            $this->save();
        }
    }
}