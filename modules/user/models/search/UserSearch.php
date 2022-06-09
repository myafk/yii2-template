<?php

namespace app\modules\user\models\search;

use app\components\helpers\DateHelper;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\user\models\User;

/**
 * UserSearch represents the model behind the search form of `app\modules\user\models\User`.
 */
class UserSearch extends User
{

    public $fullName;
    public $role;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'email', 'fullName', 'function', 'phone', 'role'], 'string'],
            [['created_at', 'updated_at', 'last_visit_at'], 'date', 'format' => DateHelper::FORMAT_DATE_RANGE]
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => ['id', 'username', 'email', 'fullName', 'function', 'phone', 'status', 'created_at'],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', self::getFullNameQuery(''), $this->fullName])
            ->andFilterWhere(['like', 'function', $this->function])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        if ($this->role) {
            $query->leftJoin('auth_assignment auth', 'auth.user_id = users.id');
            $query->andWhere(['auth.item_name' => $this->role]);
        }

        if ($this->created_at) {
            $query->andWhere(DateHelper::getBetweenQuery($this->created_at));
        }
        if ($this->updated_at) {
            $query->andWhere(DateHelper::getBetweenQuery($this->updated_at, 'updated_at'));
        }
        if ($this->last_visit_at) {
            $query->andWhere(DateHelper::getBetweenQuery($this->last_visit_at, 'last_visit_at'));
        }

        return $dataProvider;
    }
}
