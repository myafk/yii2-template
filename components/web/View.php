<?php

namespace app\components\web;

class View extends \yii\web\View
{
    public function beginTemplatePanel($params = [])
    {
        $params['bodyClass'] = $params['bodyClass'] ?? 'card-body';
        return $this->beginContent('@app/views/layouts/adminlte/template/card.php', $params);
    }

    public function endTemplatePanel()
    {
        $this->endContent();
    }

    public function beginModalPanel($params = [])
    {
        return $this->beginContent('@app/views/layouts/adminlte/template/modal.php', [
            'params' => $params
        ]);
    }

    public function endModalPanel()
    {
        $this->endContent();
    }
}