<?php

namespace app\modules\upload\models\form;

use app\modules\upload\models\Attachment;

class AttachmentTitleForm extends Attachment
{

    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['sort'], 'integer'],
        ];
    }

}