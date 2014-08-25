<?php
/**
 * @var $this yii\web\View
 * @var $model treschelet\aviasales\models\Aviasales
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\DatePicker;
?>
<?php $form = ActiveForm::begin(['options' => ['class' => 'ticket-search row text-left']]);?>
    <div class="col-xs-12"><?= $form->field($model, 'twoways', ['enableLabel' => false])->radioList($model->getWays())?><hr></div>
    <div class="col-xs-5">
        <?= $form->field($model, 'origin', ['enableError' => false])->textInput()?>
        <?= $form->field($model, 'depart', ['enableError' => false])->widget(DatePicker::className(), [
            'language' => Yii::$app->language,
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ])?>
        <div class="ages">
            <?= $form->field($model, 'adults')->dropDownList([1 => 1,2,3,4,5,6,7,8,9], ['class' => 'inout-lg'])?>
            <?= $form->field($model, 'children')->dropDownList([0,1,2,3,4,5,6], ['class' => 'inout-lg'])?>
            <?= $form->field($model, 'infants')->dropDownList([0,1,2,3,4,5,6], ['class' => 'inout-lg'])?>
        </div>
    </div>
    <div class="col-xs-2">
        <?= Html::button('<i class="fa fa-lg fa-arrows-h"></i>', ['id' => 'swap', 'class' => 'btn btn-primary'])?>
    </div>
    <div class="col-xs-5">
        <?= $form->field($model, 'destination', ['enableError' => false])->textInput()?>
        <?= $form->field($model, 'return', ['enableError' => false])->widget(DatePicker::className(), [
            'language' => Yii::$app->language,
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ])?>
        <?= $form->field($model, 'class')->radioList($model->getClass())?>
    </div>
    <div class="col-xs-12">
        <?= Html::submitButton('<i class="fa fa-search"></i> НАЙТИ БИЛЕТЫ', ['class' => 'btn btn-lg btn-warning'])?>
    </div>
<?php ActiveForm::end()?>