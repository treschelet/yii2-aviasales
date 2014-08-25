<?php
/**
 * @var $this yii\web\View
 * @var $model treschelet\aviasales\models\Aviasales
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\DatePicker;
?>
<?php $form = ActiveForm::begin(['options' => ['class' => 'ticket-search-form']]);?>
<div class="clearfix text-left">
    <div class="col-xs-12 ways"><?= $form->field($model, 'twoways', ['enableLabel' => false])->radioList($model->getWays())?><hr></div>
    <div class="col-xs-5">
        <?= $form->field($model, 'origin', ['enableLabel' => false, 'enableError' => false])->textInput()?>
        <?= $form->field($model, 'depart', ['enableLabel' => false, 'enableError' => false])->widget(DatePicker::className(), [
            'language' => Yii::$app->language,
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ])?>
        <div class="ages">
            <?= $form->field($model, 'adults')->dropDownList([1 => 1,2,3,4,5,6,7,8,9])?>
            <?= $form->field($model, 'children')->dropDownList([0,1,2,3,4,5,6])?>
            <?= $form->field($model, 'infants')->dropDownList([0,1,2,3,4,5,6])?>
        </div>
    </div>
    <div class="col-xs-2 text-center">
        <?= Html::button('<i class="fa fa-lg fa-arrows-h"></i>', ['id' => 'swap', 'class' => 'btn btn-primary'])?>
    </div>
    <div class="col-xs-5">
        <?= $form->field($model, 'destination', ['enableLabel' => false, 'enableError' => false])->textInput()?>
        <?= $form->field($model, 'return', ['enableLabel' => false, 'enableError' => false])->widget(DatePicker::className(), [
            'language' => Yii::$app->language,
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ])?>
        <?= $form->field($model, 'class')->radioList($model->getClass(), [
            'class' => 'btn-group',
            'data-toggle' => 'buttons',
            'itemOptions' => [
                'container' => false,
                'labelOptions' => ['class' => 'btn btn-primary'],
             ]
        ])?>
    </div>
    <div class="col-xs-12 text-center">
        <?= Html::submitButton('<i class="fa fa-search"></i> НАЙТИ БИЛЕТЫ', ['class' => 'btn btn-lg btn-warning'])?>
    </div>
</div>
<?php ActiveForm::end()?>