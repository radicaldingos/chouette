<?php

namespace app\components;

use Yii;
use yii\base\Event;
use app\models\RequirementLog;
use yii\base\Exception;

class RequirementLogEventHandler
{
    public function log(Event $event)
    {
        $log = new RequirementLog();
        $log->event = $event->name;
        $log->requirement_id = $event->sender->id;
        $log->user_id = Yii::$app->user->id;
        $log->date = time();
        if (! $log->save()) {
            throw new Exception('Log has not been saved.');
        }
    }
}