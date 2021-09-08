<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "link".
 *
 * @property int $id
 * @property string $url Url
 * @property string $hash Hash
 * @property int|null $follows_cnt Follows actions count
 * @property int|null $follows_limit Follows limit
 * @property string|null $expired_at
 * @property string $created_at
 * @property string|null $updated_at
 */
class Link extends \common\components\BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'required', 'on' => [self::SCENARIO_CREATE,self::SCENARIO_UPDATE]],
            [['url'], 'url', 'on' => [self::SCENARIO_CREATE,self::SCENARIO_UPDATE]],
            [['url'], 'string', 'max' => 2048, 'on' => [self::SCENARIO_CREATE,self::SCENARIO_UPDATE]],
            [['follows_cnt','follows_limit'], 'integer', 'on' => [self::SCENARIO_CREATE,self::SCENARIO_UPDATE]],
            [['follows_cnt','follows_limit'], 'default', 'value' => self::VALUE_OFF, 'on' => [self::SCENARIO_CREATE]],
            [['expired_at', 'created_at', 'updated_at'], 'safe', 'on' => [self::SCENARIO_CREATE,self::SCENARIO_UPDATE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url' => Yii::t('app', 'Url'),
            'hash' => Yii::t('app', 'Hash'),
            'follows_cnt' => Yii::t('app', 'Follows Count'),
            'follows_limit' => Yii::t('app', 'Follows Limit'),
            'expired_at' => Yii::t('app', 'Expired At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'datetimeBehavior' => [
                'class'              => \common\components\behaviors\DatetimeBehavior::className(),
                'createdAtAttribute' => ['created_at', 'updated_at'],
                'updatedAtAttribute' => ['updated_at'],
            ],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        $result = parent::beforeSave($insert);
        if ($result && $insert) {
            $this->hash = self::getRandomString(8);
        }
        return $result;
    }

    /**
     * Check is URL is relevant consdidering follows and expires
     */
    public function getIsRelevant()
    {

    }
}