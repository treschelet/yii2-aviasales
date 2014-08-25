<?php
/**
 * @var $this yii\web\View
 * @var $model treschelet\aviasales\models\Aviasales
 */
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin();?>
    <?= $form->field($model, 'twoways', ['enableLabel' => false])->radioList($model->getWays())?>
    <?= $form->field($model, 'origin')->textInput()?>
    <?= $form->field($model, 'destination')->textInput()?>
    <?= $form->field($model, 'depart')->textInput()?>
    <?= $form->field($model, 'return')->textInput()?>
    <?= $form->field($model, 'adults')->dropDownList([1 => 1,2,3,4,5,6,7,8,9])?>
    <?= $form->field($model, 'children')->dropDownList([0,1,2,3,4,5,6])?>
    <?= $form->field($model, 'infants')->dropDownList([0,1,2,3,4,5,6])?>
    <?= $form->field($model, 'class')->radioList($model->getClass())?>
<?php ActiveForm::end()?>