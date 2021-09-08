<?php

/**
 * @var yii\web\View $this
 * @var int $timer
 * @var \frontend\models\Link $link
 */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Redirect');

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('', '#', ['class' => 'jsLoading']) ?>

    <div class="alert alert-info">
        <div class="container-fluid">
            <div class="contentInner text-center">
                <p><?= Yii::t('app','You will be redirected to the requested page in {seconds}',['seconds' => Html::tag('span',$timer,['class' => 'jsTimer'])]) ?></p>
                <p><?= Yii::t('app','If nothing happens click the link bellow') ?></p>
                <p><?= Html::a($link->url, $link->url, ['target' => '_blank']) ?></p>
            </div>
        </div>
    </div>

</div>

<?php $this->registerJs("
    var timer = {$timer};
    setInterval(function(e){
        timer = timer - 1;
        $('.jsTimer').html(timer);
        if(timer <= 0){
            window.location = '{$link->url}';
        }
    },1000);
")?>