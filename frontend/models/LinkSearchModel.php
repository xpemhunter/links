<?php

namespace frontend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * LinkSearchModel represents the model behind the search form about `frontend\models\Link`.
 */
class LinkSearchModel extends Link
{
    /**
     * @var int
     */
    public $limit = 10;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * @return string
     */
    public function formName()
    {
        return 'search';
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params = [])
    {
        $query = Link::find();

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query'         => $query,
            'sort'          => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination'    => ['pageSize' => $this->limit],
        ]);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
