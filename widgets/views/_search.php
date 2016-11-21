<?php

use kozlovsv\crud\widgets\CrudField;
use kozlovsv\crud\widgets\SearchActiveForm;
use kozlovsv\widgets\FilterReset;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model \yii\db\ActiveRecord */
/* @var $form SearchActiveForm */
/* @var $fields array */

?>

<?php $form = SearchActiveForm::begin(); ?>

    <?php foreach ($fields as $params) {
        echo CrudField::widget([
            'model' => $model,
            'form' => $form,
            'params' => $params,
        ]);
    } ?>

    <?= Html::submitButton(Html::icon('search'), ['class' => 'btn btn-default']) ?>
    <?= FilterReset::widget([
        'model' => $model,
        'url' => ['index'],
    ]); ?>

<?php SearchActiveForm::end(); ?>