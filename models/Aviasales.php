<?php
/**
 * Created by Treschelet.
 * Date: 22.08.14
 */

namespace treschelet\aviasales\models;

use Yii;
use yii\base\Model;

class Aviasales extends Model
{
    public $origin;
    public $destination;
    public $depart;
    public $return = '';
    public $adults = 1;
    public $children = 0;
    public $infants = 0;
    public $class = false;
    public $locale = 'ru';
    public $twoway = false;

    public function rules()
    {
        return [
            [['origin', 'destination', 'depart'], 'required'],
            [['depart', 'return'], 'date'],
            ['adults', 'integer', 'min' => 1, 'max' => 9],
            [['children', 'infants'], 'integer', 'min' => 0, 'max' => 6],
            [['class', 'twoway'], 'boolean'],
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
} 