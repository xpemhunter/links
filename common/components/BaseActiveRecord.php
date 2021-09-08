<?php

namespace common\components;

use Yii;

/**
 * Class BaseActiveRecord
 * @package common\components
 */
class BaseActiveRecord extends \yii\db\ActiveRecord
{
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CREATE = 'create';

    const VALUE_ON = 1;
    const VALUE_OFF = 0;
    /** @var array  */
    public static $availableSwitchers = [
        self::VALUE_OFF,
        self::VALUE_ON,
    ];

    /**
     * @param string $attribute
     * @return string
     */
    public function getCustomLabel($attribute)
    {
        $labels = static::getCustomLabels($attribute);
        return (isset($labels[$this->$attribute])) ? $labels[$this->$attribute] : null;
    }

    /**
     * Get list of custom label
     * @param string $attribute Attribute name
     * @return array
     */
    public static function getCustomLabels($attribute)
    {
        switch($attribute) {
            case 'enabled':
                return [
                    self::VALUE_OFF => Yii::t('app','No'),
                    self::VALUE_ON  => Yii::t('app','Yes'),
                ];
                break;
        }
        return [];
    }

    /**
     * Get label for value for particular attribute
     * @param string $attribute Attribute name
     * @return string
     */
    public function getLabelValue($attribute)
    {
        $labels = static::getCustomLabels($attribute);
        if (!empty($labels)){
            if (!empty($labels[$this->{$attribute}])){
                return $labels[$this->{$attribute}];
            }
        }
        return $this->{$attribute};
    }

    /**
     * Get random string
     * @param int $length Length of the generated string
     * @param string $symbols List of all available symbols for generating
     * @return false|string
     */
    public static function getRandomString($length = 8, $symbols = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz')
    {
        return substr(str_shuffle($symbols), 0, $length);
    }
}