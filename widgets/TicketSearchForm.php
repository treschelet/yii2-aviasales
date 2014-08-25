<?php

namespace treschelet\aviasales\widgets;

use Yii;
use treschelet\aviasales\models\Aviasales;

class TicketSearchForm extends \yii\base\Widget
{
    public function run()
    {
        $model = new Aviasales();
        $model->load(Yii::$app->request->post());
        return $this->render('ticket_form', ['model' => $model]);
    }
}
