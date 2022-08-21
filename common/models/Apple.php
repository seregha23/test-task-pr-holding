<?php

namespace common\models;

use ErrorException;
use yii\db\ActiveRecord;

/**
 *
 * @property int $id [int(11)]
 * @property int $color_id [int(10)]
 * @property int $status_id [int(10)]
 * @property int $size [decimal(10,1)]
 * @property string $create_at [datetime]
 * @property string $fell_at [datetime]
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

    public function getColor(): ?string {
        return self::$colors[$this->color_id] ?? null;
    }

    public function getStatus(): ?string {
        return self::$statuses[$this->status_id] ?? null;
    }

    public function fallToGround(): void {
        if ($this->status_id == self::STATUS_ROTTEN_APPLE) {
            throw new ErrorException('Яблоко уже гнилое на земле');
        }
        $this->status_id = self::STATUS_ON_GROUND;
        $this->fell_at   = date( 'Y-m-d H:i:s', time() );
        $this->save();
    }

    /** @throws ErrorException */
    public function eat(int $size): void {
        if ($this->status_id == self::STATUS_ON_TREE) {
            throw new ErrorException('Съесть нельзя, яблоко на дереве');
        }

        if ($this->status_id == self::STATUS_ROTTEN_APPLE) {
            throw new ErrorException('Съесть нельзя, яблоко гнилое');
        }

        if ($size > $this->size) {
            throw new ErrorException('Нельзя откусить от яблока больше, чем осталось');
        }

        $this->size -= $size;
        $this->save();
        if ($this->size <= 0) {
            $this->delete();
        }
    }

    public function afterFind(): void {
        if ( $this->fell_at && (time() - strtotime($this->fell_at)) > 5 * 60 * 60  ) {
            $this->status_id = self::STATUS_ROTTEN_APPLE;
            $this->save();
        }
    }
}