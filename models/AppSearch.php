<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\app;

/**
 * AppSearch represents the model behind the search form of `app\models\app`.
 */
class AppSearch extends app
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'room_id'], 'integer'],
            [['start', 'payment', 'status', 'feedback'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = app::find();

        // add conditions that should always apply here

        if(!Yii::$app->user->identity->isAdmin()) {

            $query->andFilterWhere([ //копируем срочку снизу и дописываем
                'user_id' => Yii::$app->user->identity->id
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // настраиваем пагинацию
            'pagination' => ['pageSize' => 6],
            //
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1'); //было закоментированно
            return $dataProvider;
        }


        //к удалению:
        // grid filtering conditions
        // $query->andFilterWhere([
        //     'id' => $this->id,
        //     'user_id' => $this->user_id,
        //     'room_id' => $this->room_id,
        //     'start' => $this->start,
        // ]);

        // $query->andFilterWhere(['like', 'payment', $this->payment])
        //     ->andFilterWhere(['like', 'status', $this->status])
        //     ->andFilterWhere(['like', 'feedback', $this->feedback]);

        return $dataProvider;
    }
}
