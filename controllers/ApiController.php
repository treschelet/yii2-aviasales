<?php
/**
 * Created by Treschelet.
 * Date: 05.08.14
 */

namespace treschelet\aviasales\controllers;

use Yii;
use yii\rest\Controller;
use treschelet\aviasales\components\Aviasales;
use treschelet\aviasales\models\Aviasales as ASModel;

class ApiController extends Controller
{
    /** @var $AS Aviasales */
    protected $AS;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->AS = new Aviasales([
                'marker' => $this->module->marker,
                'token' => $this->module->token,
            ]);
            return true;
        } else {
            return false;
        }
    }

    public function actionIndex()
    {
        return ['version' => $this->module->version];
    }

    public function actionSearch($type = 'tickets')
    {
        switch ($type) {
            case 'tickets':
                $model = new ASModel();
                $model->load(Yii::$app->request->post());
                //$model->load(Yii::$app->request->queryParams);
                if (!$model->twoways)
                    $model->return = '';
                return $this->AS->getTickets($model);
            default:
                return [];
        }
    }

    public function actionBooking($type = 'tickets', $sid, $uid)
    {
        switch ($type) {
            case 'tickets':
                return $this->AS->bookingTickets($sid, $uid);
            default:
                return [];
        }
    }

    public function actionPlaces($term = '')
    {
        if (empty($term))
            return [];
        return $this->AS->getPlaces($term);
    }
} 