<?php

use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\modules\log\models\Log;
use kozlovsv\crud\widgets\ActionColumn;
use kozlovsv\crud\helpers\ModelPermission;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\SearchPanel;
use kozlovsv\crud\widgets\Select2;
use kozlovsv\crud\widgets\ToolBarPanel;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel kozlovsv\crud\modules\log\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Логи приложения';
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin([
    'id' => 'pjax-content',
    'formSelector' => false,
]);

echo ToolBarPanel::widget(
    [
        'buttons' => [
            Html::a('Очистить журнал', ['truncate'], ['class' => 'btn btn-danger', 'data' => ['confirm' => 'Вы действительно хотите удалить все записи из журнала']]),
            SearchPanel::widget([
                'model' => $searchModel,
                'resetUrl' => 'index',
                'attributes' => [
                    'level' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => Select2::class,
                        'options' => [
                            'data' => Log::levelMap(),
                            'pluginOptions' => ['minimumResultsForSearch' => -1, 'width' => '170px']
                        ],
                    ],
                    'category' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => Select2::class,
                        'options' => [
                            'data' => Log::categoryMap(),
                            'pluginOptions' => ['minimumResultsForSearch' => -1, 'width' => '170px'],
                        ],
                    ],
                    'message',
                ],
            ]),
        ]
    ]
);

echo GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'isModal' => true,
        'permissionCategory' => $searchModel::tableName(),
        'columns' => [
            [
                'class' => yii\grid\CheckboxColumn::class,
                'checkboxOptions' => []

            ],
            'id',
            'levelLabel',
            'category',
            [
                'attribute' => 'log_time',
                'format' => 'datetime',
            ],
            [
                'attribute' => 'message',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<pre style="max-height: 100px; overflow: auto;">' . $model->message . '</pre>';
                },
            ],
        ],
        'actionColumnsAfter' => [
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',
                'visible' => ModelPermission::canDelete($searchModel::tableName()),
                'header' => Html::a(Html::icon('remove'), ['#'], ['title' => 'Удалить', 'aria-label' => 'Удалить', 'id' => 'delete-all-button']),
                'headerOptions' => ['class' => 'action-column action-header-button'],
            ],
        ]
    ]
);

Pjax::end();

$js = <<<JS
        var lotListId = [];  
        $('body').on('click', '#delete-all-button', function(event) { 
            ids = [];
            $('input[name="selection[]"]:checked ').each(function() {
                ids.push(this.value); 
            });  
            if(ids.length==0)
            {
                return false;
            }
            if (!confirm('Удалить выделенные записи?')) return false;
           $.ajax({
                url: '/log/default/delete-all' ,
                type: 'post',
                data: {
                    ids:  ids                             
                },
                success: function (data) {
                     //$.pjax.reload({container: '#pjax-content'});
                     document.location.reload(true);
                }
            });
            return false;
        });
JS;

$this->registerJs($js, yii\web\View::POS_END);