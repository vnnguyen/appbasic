<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Post1;

/**
 * post1search represents the model behind the search form about `app\models\Post1`.
 */
class post1search extends Post1
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['offer_by', 'department', 'content', 'amount', 'status', 'payment', 'accom_doc', 'note', 'deadline', 'cost'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post1::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'deadline' => $this->deadline,
        ]);

        $query->andFilterWhere(['like', 'offer_by', $this->offer_by])
            ->andFilterWhere(['like', 'department', $this->department])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'payment', $this->payment])
            ->andFilterWhere(['like', 'accom_doc', $this->accom_doc])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'cost', $this->cost]);

        return $dataProvider;
    }
}
