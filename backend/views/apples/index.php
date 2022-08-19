<?php

/** @var View $this */
/* @var $apples Apple[] */
/* @var $apple Apple */

use common\models\Apple;
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
                    <div class="apples__item">
                        <div class="apples__id"><?= $apple->id ?>1</div>
                        <div class="apples__color"><?= $apple->color ?>2</div>
                        <div class="apples__size"><?= $apple->size ?>3</div>
                        <div class="apples__status"><?= $apple->status ?>4</div>
                        <div class="apples__create-date"><?= $apple->create_date ?>5</div>
                        <div class="apples__full-date"><?= $apple->fall_date ?>6</div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>

<?php

$js = <<<EOF
    $('#apples-generate').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/apples/ajax-generate-random',
            dataType: 'json',
            success: function (data) {
                //$('.alert-message').html(data.message);
            }
        });
    });

EOF;
$this->registerJs($js);

