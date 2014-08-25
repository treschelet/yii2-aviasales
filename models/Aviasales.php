<?php
/**
 * Created by Treschelet.
 * Date: 22.08.14
 */

namespace treschelet\aviasales\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;

class Aviasales extends Model
{
    public $origin;
    public $destination;
    public $depart;
    public $return = '';
    public $adults = 1;
    public $children = 0;
    public $infants = 0;
    public $class = 0;
    public $locale = 'ru';
    public $twoways = 0;

    public function rules()
    {
        return [
            [['origin', 'destination', 'depart'], 'required'],
            [['depart', 'return'], 'date'],
            ['adults', 'integer', 'min' => 1, 'max' => 9],
            [['children', 'infants'], 'integer', 'min' => 0, 'max' => 6],
            [['class', 'twoway'], 'in', 'range' => [0,1]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'origin' => 'Откуда',
            'destination' => 'Куда',
            'depart' => 'Дата вылета',
            'return' => 'Дата возвращения',
            'adults' => 'Взрослые',
            'children' => 'Дети 2-12',
            'infants' => 'Дети до 2-х',
            'class' => 'Класс перелета'
        ];
    }

    public function getWays()
    {
        return [
            'В одну сторону',
            'Туда и обратно',
        ];
    }

    public function getClass()
    {
        return [
            'Эконом',
            'Бизнес',
        ];
    }

    public static function classSwitch($index, $label, $name, $checked, $value)
    {
        return Html::radio($name, $checked, [
            'value' => $value,
            'label' => $label,
            'container' => false,
            'labelOptions' => [
                'class' => 'btn btn-primary'.($checked ? ' active' : ''),
            ],
        ]);
    }
} 