<?php

namespace common\helpers;

use common\models\Apple;

class ApplesProvider {

    public static function generateRandomApples(): void {
        $countApples = rand(1, 20);
        for ($i = 1; $i <= $countApples; $i++) {
            $apple              = new Apple();
            $apple->color_id    = array_rand(Apple::$colors);
            $apple->status_id   = array_rand(Apple::$statuses);
            $apple->size        = 100;
            $apple->create_at = date( 'Y-m-d H:i:s', time() );
            $apple->save(false);
        }
    }

    public static function regenerateRandomApples(): void {
        Apple::deleteAll();
        self::generateRandomApples();
    }
}