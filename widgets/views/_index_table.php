<?php
use yii\widgets\Pjax;

/** @var string $grid */
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 50px">
            <?php
            Pjax::begin([
                'id' => 'pjax-table',
                'formSelector' => false,
                'scrollTo' => 1
            ]);
            echo $grid;
            Pjax::end();
            ?>
    </div>
</div>