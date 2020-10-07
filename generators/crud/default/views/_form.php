<?php

/* @var $this yii\web\View */
/* @var $generator app\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$class = $generator->getModelClass();
$model = new $class();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use kozlovsv\crud\helpers\Html;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->getModelClass(), '\\') ?> */

<?php if ($generator->enableModal) echo "<?php Pjax::begin(['id' => 'pjax-form']);?>\n";?>
<?="<?php ";?>$form = ActiveForm::begin();?>

<?="<?php\n";?>
echo Html::tag('h1', Html::encode($this->title), ['class' => 'form-header']);
echo FormBuilder::widget([
        'form' => $form,
        'model' => $model,
        'attributes' => [
<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo "            '".  $attribute . ":fa:user',\n";
    }
} ?>
        ]
    ]
);

echo ToolBarPanelContainer::widget([
        'buttonsRight' => [
            CrudButton::saveButton(),
            CrudButton::cancelButton(),
        ],
        'options' => ['class' => 'form-group', 'style' => 'margin-top: 20px; margin-right: 0'],
    ]
);
?>

<?="<?php ";?>ActiveForm::end();?>
<?php if ($generator->enableModal) echo "<?php Pjax::end();?>\n";?>