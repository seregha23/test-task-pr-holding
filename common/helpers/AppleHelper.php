<?php

namespace common\helpers;

use common\models\Apple;
use ErrorException;

class AppleHelper {

    public function generateRandom(): void {
        Apple::deleteAll();
        $countApples = mt_rand(1,20);
        for ($i = 1; $i <= $countApples, $i++;) {
            $apple              = new Apple();
            $apple->color_id    = rand(Apple::COLOR_GREEN, Apple::COLOR_MULTI);
            $apple->status_id   = rand(Apple::STATUS_ON_TREE, Apple::STATUS_ROTTEN_APPLE);
            $apple->create_date = date( 'Y-m-d H:i:s', rand( strtotime("-1 week"), time()) );
            $apple->save(false);
        }
    }

    public static function fallToGround(int $id): void {
        $apple = Apple::find()->andWhere(['id' => $id])->one();
        $apple->status_id = Apple::STATUS_ON_GROUND;
        $apple->save();
    }

    /**
     * @throws ErrorException
     */
    public static function eat(int $id, int $size): void {
        $apple = Apple::find()->andWhere(['id' => $id])->one();
        if ($apple->status_id == Apple::STATUS_ON_TREE) {
            throw new ErrorException('Apple on a tree ');
        }

        $apple->size -= $size;
        $apple->save();
        if ($apple->size <= 0) {
            $apple->deleteAll(['id' => $id]);
        }
    }
}