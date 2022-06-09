<?php

namespace app\modules\profile\models\form;

use app\modules\upload\models\Attachment;
use app\modules\user\models\exception\ProfileException;
use app\modules\user\models\User;
use Yii;
use yii\base\Model;

class AvatarForm extends Model
{

    public $avatar_id;

    public function init()
    {
        if (Yii::$app->user->isGuest) {
            throw new ProfileException('Please log in.', ProfileException::NOT_LOG_IN);
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['avatar_id', 'integer'],
            ['avatar_id', 'validateAttachment'],
        ];
    }

    public function validateAttachment()
    {
        $avatar = Attachment::findOne(['id' => $this->avatar_id, 'user_id' => Yii::$app->user->id]);
        if (!$avatar) {
            $this->addError('avatar_id', Yii::t('main', 'Ошибка загрузки'));
        }
    }

    public function change()
    {
        if ($this->validate()) {
            /* @var $user User */
            $user = Yii::$app->user->identity;
            $user->avatar_id = $this->avatar_id;
            $user->update(false, ['avatar_id']);
            return true;
        }

        return false;
    }
}