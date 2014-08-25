<?php
/**
 * Created by Treschelet.
 * Date: 25.08.14
 */

namespace treschelet\aviasales\widgets;


class TicketSearchAsset extends AssetBundle
{
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/../assets');
        $this->setupAssets('js', ['css/ticketsearch']);
        parent::init();
    }
} 