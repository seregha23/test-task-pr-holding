<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 *
 * @property int $id [int(11)]
 * @property int $color_id [int(10)]
 * @property int $status_id [int(10)]
 * @property int $size [decimal(10,1)]
 * @property int $create_date [timestamp]
 * @property int $fall_date [timestamp]
 *
 * @property-read string $status
 * @property-read string $color
 */
class Apple extends ActiveRecord {
    const COLOR_GREEN = 1;
    const COLOR_RED   = 2;
    const COLOR_MULTI = 3;

    public static array $colors = [
        Apple::COLOR_GREEN => 'зеленый',
        Apple::COLOR_RED   => 'красный',
        Apple::COLOR_MULTI => 'зелено-красный',
    ];


    const STATUS_ON_TREE      = 1;
    const STATUS_ON_GROUND    = 2;
    const STATUS_ROTTEN_APPLE = 3;

    public static array $statuses = [
        Apple::STATUS_ON_TREE      => 'на дереве',
        Apple::STATUS_ON_GROUND    => 'на земле',
        Apple::STATUS_ROTTEN_APPLE => 'гнилое яблоко',
    ];

    public ?int $color_id = null;
    public ?int $status_id = null;
    public ?int $size = null;
    public ?string $create_date = null;
    public ?string $fall_date = null;

    public static function tableName(): string {
        return '{{%apples}}';
    }


    public function getColor(): ?string {
        return Apple::$colors[$this->color_id] ?? null;
    }

    public function getStatus(): ?string {
        return Apple::$statuses[$this->status_id] ?? null;
    }

 }