<?php

namespace common\components;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class BaseForm
 * @package common\components
 */
class BaseForm extends Model
{
    //scenarios
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const VALUE_OFF = 0;
    const VALUE_ON = 1;

    //events
    const EVENT_BEFORE_SET_ATTRIBUTES       = 'beforeSetAttributes';
    const EVENT_AFTER_SET_ATTRIBUTES        = 'afterSetAttributes';
    const EVENT_BEFORE_SET_MODEL_ATTRIBUTES = 'beforeSetModelAttributes';
    const EVENT_AFTER_SET_MODEL_ATTRIBUTES  = 'afterSetModelAttributes';
    const EVENT_BEFORE_SAVE                 = 'beforeSave';
    const EVENT_AFTER_SAVE                  = 'afterSave';

    /**
     * @var \common\components\BaseActiveRecord
     */
    public $model;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();

        //init events
        $this->on(self::EVENT_BEFORE_SET_ATTRIBUTES, [$this, 'beforeSetAttributes']);
        $this->on(self::EVENT_AFTER_SET_ATTRIBUTES,  [$this, 'afterSetAttributes']);
        $this->on(self::EVENT_BEFORE_SET_MODEL_ATTRIBUTES, [$this, 'beforeSetModelAttributes']);
        $this->on(self::EVENT_AFTER_SET_MODEL_ATTRIBUTES,  [$this, 'afterSetModelAttributes']);
        $this->on(self::EVENT_BEFORE_SAVE, [$this, 'beforeSave']);
        $this->on(self::EVENT_AFTER_SAVE,  [$this, 'afterSave']);
    }

    /**
     * @return \common\components\BaseActiveRecord
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param \common\components\BaseActiveRecord $model
     * @param bool $setAttributes
     * @param null|array $attributesNames array of attributes names
     */
    public function setModel($model,$setAttributes = true, $attributesNames = null)
    {
        $this->model = $model;
        if($setAttributes){
            $this->trigger(self::EVENT_BEFORE_SET_ATTRIBUTES);
            $this->setAttributes($this->model->getAttributes($attributesNames));
            $this->trigger(self::EVENT_AFTER_SET_ATTRIBUTES);
        }
    }

    /**
     * Save event
     * @param bool $runValidation
     * @param array|null $attributes
     * @return bool
     */
    public function save($runValidation = true, $attributes = null)
    {
        //validate from attributes
        if ($runValidation && !$this->validate()){
            return false;
        }

        //set model attributes
        $this->trigger(self::EVENT_BEFORE_SET_MODEL_ATTRIBUTES);
        $this->model->setAttributes($attributes?$attributes:$this->getAttributes());
        $this->trigger(self::EVENT_AFTER_SET_MODEL_ATTRIBUTES);

        //save model
        $this->trigger(self::EVENT_BEFORE_SAVE);
        if(!$this->model->save($runValidation)) {
            $this->addErrors($this->model->getErrors());
            return false;
        }
        $this->trigger(self::EVENT_AFTER_SAVE);

        return true;
    }

    /**
     * Before set forms attributes handler
     * @return bool
     */
    public function beforeSetAttributes()
    {
        //custom code here...
        return true;
    }

    /**
     * After set forms attributes handler
     * @return void
     */
    public function afterSetAttributes()
    {
        //custom code here...
    }

    /**
     * Before set model attributes handler
     * @return bool
     */
    public function beforeSetModelAttributes()
    {
        //custom code here...
        return true;
    }

    /**
     * After set model attributes handler
     * @return void
     */
    public function afterSetModelAttributes()
    {
        //custom code here...
    }

    /**
     * Before save model handler
     * @return bool
     */
    public function beforeSave()
    {
        //custom code here...
        return true;
    }

    /**
     * After save model handler
     * @return void
     */
    public function afterSave()
    {
        //custom code here...
    }
}