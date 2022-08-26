<?php

namespace common\helpers;

use common\models\Apple;

class ApplesProvider {
    public static function generateRandomApples(): void {
        $countApples = rand(1, 20);
        for ($i = 1; $i <= $countApples; $i++) {
            $apple             = new Apple();
            $apple->color_id   = array_rand(Apple::$colors);
            $apple->status_id  = Apple::STATUS_ON_TREE;
            $apple->save();
        }
    }

    public static function regenerateRandomApples(): void {
        Apple::deleteAll();
        self::generateRandomApples();
    }
}