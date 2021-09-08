<?php

namespace common\components\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;

/**
 * DatetimeBehavior automatically fills the specified attributes with the datetime expression
 *
 * To use DatetimeBehavior, insert the following code to your ActiveRecord class:
 * ```php
 * use \common\components\behaviors\TimestampBehavior;
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => TimestampBehavior::className(),
 *             'createdAtAttribute' => 'create_at',
 *             'updatedAtAttribute' => 'updated_at,
 *         ],
 *     ];
 * }
 */
class DatetimeBehavior extends Behavior
{
    /**
     * @var string|array the attribute(s) that should be updated when object created
     */
    public $createdAtAttribute;

    /**
     * @var string|array the attribute(s) that should be updated within each object update
     */
    public $updatedAtAttribute;

    /**
     * @var string
     */
    public $value;

    public function init(){
        parent::init();
        if (empty($this->value)){
            $this->value = date("Y-m-d H:i:s");
        }
    }

    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT   => 'beforeInsert',
            BaseActiveRecord::EVENT_BEFORE_UPDATE   => 'beforeUpdate',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeInsert($event)
    {
        if ($this->createdAtAttribute) {
            if (!is_array($this->createdAtAttribute)) {
                $this->createdAtAttribute = [$this->createdAtAttribute];
            }

            foreach ($this->createdAtAttribute as $attributeName) {
                if ($attributeName && $this->owner->hasAttribute($attributeName)
                    && empty($this->owner->{$attributeName})) {
                    $this->owner->{$attributeName} = $this->value;
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeUpdate($event)
    {
        if ($this->updatedAtAttribute) {
            if (!is_array($this->updatedAtAttribute)) {
                $this->updatedAtAttribute = [$this->updatedAtAttribute];
            }

            foreach ($this->updatedAtAttribute as $attributeName) {
                if ($attributeName && $this->owner->hasAttribute($attributeName)){
                    $this->owner->{$attributeName} = $this->value;
                }
            }
        }
    }
}
