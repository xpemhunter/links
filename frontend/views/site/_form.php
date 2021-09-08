<?php

/* @var  yii\web\View $this */
/* @var \frontend\forms\LinkForm $formModel */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'jsForm my-3 p-3 border',
    ],
    'enableClientValidation' => false,
]); ?>

    <div class="row">
        <div class="col-xs-8 col-md-8">
            <?= $form->field($formModel, 'url')->textInput(['placeholder' => Yii::t('app', 'URL')])->label(Yii::t('app', 'Shop name')) ?>
        </div>
        <div class="col-xs-2 col-md-2">
            <?= $form->field($formModel, 'follows_limit')->textInput(['type' => 'number', 'placeholder' => 0, 'min' => 0])->label(Yii::t('app', 'Follows limit')) ?>
        </div>
        <div class="col-xs-2 col-md-2">
            <?= $form->field($formModel, 'expire_in')->textInput(['type' => 'number', 'placeholder' => 1, 'min' => 1, 'max' => 24])->label(Yii::t('app', 'Expires in (hours)')) ?>
        </div>
    </div>

    <div class="buttonBlock">
        <?= Html::a(Yii::t('app', 'Cancel'), '#', ['class' => 'btn btn-secondary jsCancelButton']) ?>
        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => $formModel->model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>