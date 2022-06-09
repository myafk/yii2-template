<?php

namespace app\modules\upload\components;

use app\modules\upload\models\Attachment;
use Yii;
use yii\base\Model;

/**
 * @property int $avatar_id
 */
trait AvatarTrait
{

    public function getAvatar()
    {
        /** @var $this Model */
        if (!$this->hasProperty('avatar_id')) {
            Yii::error('Avatar field not found');
            return false;
        }

        if (!empty($this->avatar_id)) {
            return Attachment::getAttributeAttachment($this->avatar_id);
        } else {
            return false;
        }
    }

    public function getAvatarUrl()
    {
        if ($avatar = $this->getAvatar()) {
            /** @var $avatar Attachment */
            return $avatar->getTitleUrl();
        } else {
            return $this->getDefaultAvatarUrl();
        }
    }

    public function getAvatarThumbUrl()
    {
        if ($avatar = $this->getAvatar()) {
            /** @var $avatar Attachment */
            return $avatar->getThumbUrl();
        } else {
            return $this->getDefaultAvatarUrl();
        }
    }

    public function getDefaultAvatarUrl()
    {
        return '/resource/img/default-avatar.jpg';
    }

}