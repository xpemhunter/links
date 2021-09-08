<?php

namespace frontend\forms;

use Yii;
use frontend\models\Link;

/**
 * Class LinkForm
 * @property Link $model
 */
class LinkForm extends \common\components\BaseForm
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var int
     */
    public $follows_limit;

    /**
     * @var int
     */
    public $expire_in;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'filter', 'filter' => 'trim', 'on' => [self::SCENARIO_CREATE]],
            [['url'], 'required', 'on' => [self::SCENARIO_CREATE]],
            [['url'], 'string', 'max' => 2048, 'on' => [self::SCENARIO_CREATE]],
            [['follows_limit'], 'integer', 'on' => [self::SCENARIO_CREATE,self::SCENARIO_UPDATE]],
            [['expire_in'], 'integer', 'min' => 1, 'max' => 24, 'on' => [self::SCENARIO_CREATE,self::SCENARIO_UPDATE]],
            [['follows_limit'], 'default', 'value' => self::VALUE_OFF, 'on' => [self::SCENARIO_CREATE]],
            [['expire_in'], 'default', 'value' => self::VALUE_ON, 'on' => [self::SCENARIO_CREATE]],
            [['url', 'follows_limit', 'expire_in'], 'safe'],
        ];
    }

    public function formName()
    {
        return 'linkForm';
    }

    /**
     * @inheritDoc
     */
    public function beforeSave()
    {
        if (parent::beforeSave()) {
            if (!empty($this->expire_in)) {
                $this->model->expired_at = date('Y-m-d H:i:s', strtotime("+{$this->expire_in} hour"));
            }
        }
    }
}