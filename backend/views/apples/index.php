<?php

/** @var View $this */
/* @var $apples Apple[] */
/* @var $apple Apple */
/* @var $eatAppleForm EatAppleForm */

use common\models\Apple;
use backend\models\forms\EatAppleForm;
use yii\web\View;

?>
<div class="apples__wrapper">
    <div class="container">
        <h1>Яблоки</h1>
        <div class="apples__generate">
            <a id="apples-generate" href="javascript:void(0)">Сгенерировать яблоки</a>
        </div>
        <?php if (isset($apples)) { ?>
            <div class="apples__items">
                <?php foreach ($apples as $apple) { ?>
                    <?= $this->render('_apple', [
                            'apple'        => $apple,
                            'eatAppleForm' => $eatAppleForm,
                    ]) ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>

<?php

$js = <<<EOF

EOF;
$this->registerJs($js);

