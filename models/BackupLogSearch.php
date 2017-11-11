<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BackupLog;

/**
 * BackupLogSearch represents the model behind the search form about `app\models\BackupLog`.
 */
class BackupLogSearch extends BackupLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'schedule'], 'integer'],
            [['schema', 'artifact', 'hash', 'status', 'created_at'], 'safe'],
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
        $query = BackupLog::find();

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
            'schedule' => $this->schedule,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'schema', $this->schema])
            ->andFilterWhere(['like', 'artifact', $this->artifact])
            ->andFilterWhere(['like', 'hash', $this->hash])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
