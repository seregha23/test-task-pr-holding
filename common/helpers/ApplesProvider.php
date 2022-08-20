<?php

namespace common\helpers;

use common\models\Apple;

class ApplesProvider {

    public static function generateRandomApples(): void {
        $countApples = rand(1, 20);
        for ($i = 1; $i <= $countApples; $i++) {
            $apple              = new Apple();
            $apple->color_id    = rand(Apple::COLOR_GREEN, Apple::COLOR_MULTI);
            $apple->status_id   = rand(Apple::STATUS_ON_TREE, Apple::STATUS_ROTTEN_APPLE);
            $apple->size        = 100;
            $apple->create_at = date( 'Y-m-d H:i:s', rand( strtotime("-1 week"), time()) );
            $apple->save(false);
        }
    }

    public static function regenerateRandomApples(): void {
        Apple::deleteAll();
        self::generateRandomApples();
    }
}