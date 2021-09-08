<?php
/**
 * @var yii\web\View $this
 * @var frontend\models\LinkSearchModel $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Links');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="index jsListParent">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="tab-content padding-10">
        <div class="tab-pane in active">
            <div class="col-xs-6">
                <?= Html::a(Yii::t('app', 'Create'), ['/site/create'], ['class' => 'btn btn-primary my-3 jsUpdateItem']) ?>
                <div class="jsItemForm" style="display: none;"></div>
            </div>

            <?php \yii\widgets\Pjax::begin([
                'id' => 'linksPjax',
                'options' => [
                    'class' => 'jsPjaxContainer',
                ],
            ]) ?>

            <?= \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => '<div class="empty"><p>'.Yii::t('app', 'There is not created links yet').'</p></div>',
                'layout'=>"{items}\n{pager}\n{summary}\n",
                'columns' => [
                    [
                        'attribute' => 'url',
                        'format' =>  'raw',
                        'value' => function ($model) {
                            /* @var \frontend\models\Link $model */
                            return Html::a($model->url, ['/site/go', 'hash' => $model->hash], [
                                'data-pjax' => 0,
                            ]);
                        },
                    ],
                    'follows_cnt',
                    'follows_limit',
                    [
                        'attribute' => 'expired_at',
                        'options' => [
                            'width' => '140px',
                        ],
                        'value' => function($model) {
                            /* @var \frontend\models\Link $model */
                            return Yii::$app->dataFormatter->getFormattedTime($model->expired_at);
                        }
                    ],
                ],
            ]); ?>
            <?php \yii\widgets\Pjax::end() ?>
        </div>
    </div>

</div>

<?php $this->registerJs("
    $('body').on('click','.jsUpdateItem',function() {
        var wrapperParent = $(this).closest('.jsListParent');
        var formButton = $(this);
        $.ajax({
            url:  $(this).attr('href'),
            type: 'get',
            dataType: 'json',
            success: function (response) {
                if (response && response.form) {
                    $('.jsItemForm').html(response.form);
                    $('.jsItemForm').show();
                    formButton.hide();
                }
                return true;
            },
            error: function(err){}
        });
        return false;
    });
    $('body').on('submit','.jsForm',function() {
        var wrapperParent = $(this).closest('.jsListParent');
        var formButton = wrapperParent.find('.jsUpdateItem');
        $.ajax({
            url:  $(this).attr('action'),
            type: 'post',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response) {
                    if (response.form) {
                        $('.jsItemForm').html(response.form);
                        $('.jsItemForm').show();
                    }
                    if (response.status) {
                        var pjaxId = $(wrapperParent).find('.jsPjaxContainer').attr('id');
                        $.pjax.reload({container:'#'+pjaxId});
                        $('.jsItemForm').empty();
                        formButton.show();
                    };
                }
                return true;
            },
            error: function(err){}
        });
        return false;
    });
    $('body').on('click','.jsCancelButton',function() {
        var wrapperParent = $(this).closest('.jsListParent');
        wrapperParent.find('.jsItemForm').empty();
        wrapperParent.find('.jsUpdateItem').show();
        return false;
    });    
");
