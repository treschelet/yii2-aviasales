<?php

namespace treschelet\aviasales\widgets;

use Yii;
use treschelet\aviasales\models\Aviasales;
use yii\helpers\Url;

class TicketSearchForm extends \yii\base\Widget
{
    public $searchUrl;
    public $suggestUrl;

    public function run()
    {
        $this->registerAssets();
        $model = new Aviasales();
        $model->load(Yii::$app->request->post());
        return $this->render('ticket_form', ['model' => $model]);
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        TicketSearchAsset::register($view);
        $view->registerJs("var TSParams = {url: '".Url::to($this->searchUrl)."'};", $view::POS_HEAD);
    }
}
