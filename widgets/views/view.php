<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \yii\db\ActiveRecord */
/* @var $divClass string */
/* @var $title string */
/** @var string $content */
/** @var array $buttonsLeft */
/** @var array $buttonsRight */

$id = $model->getPrimaryKey();

?>

<div class="<?=$divClass;?>">
    <?php if (!empty($title)) : ?> <h1><?= Html::encode($title) ?></h1> <?php endif; ?>
    <div class="form-group" style="margin-bottom: 10px">
        <div class="pull-left">
            <?php foreach ($buttonsLeft as $button) { ?>
                <div class="btn-group">
                    <?= $button;?>
                </div>
            <?php } ?>
        </div>
        <div class="pull-right">
            <?php foreach ($buttonsRight as $button) { ?>
                <div class="btn-group">
                    <?= $button;?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?=$content;?>
</div>
