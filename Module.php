<?php
/**
 * Created by Treschelet.
 * Date: 10.07.14
 */

namespace treschelet\aviasales;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'treschelet\aviasales\controllers';
    public $defaultRoute = 'api';

    public $marker;
    public $token;

    public $version = '1.0';

    public function init()
    {
        parent::init();
    }

    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            $this->id => $this->id . '/api/index',
            $this->id . '/search/<type:(hotels|tickets)>' => $this->id . '/api/search',
            $this->id . '/booking/tickets/<sid>/<uid>' => $this->id . '/api/booking',
        ], false);
    }

} 