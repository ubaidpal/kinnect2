<?php

namespace App\Handlers\Events;

use App\ActivityAction;
use App\Events\ActivityLog;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class ActivityLogEventHandler
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ActivityLog  $event

     */
    public function handle(ActivityLog $event)
    {
        $options = $event->get_options();

        $option = array(
            'type' => null,
            'subject' => null,
            'subject_type' => \Config::get('constants_activity.OBJECT_TYPES.USER.NAME'),
            'object' => null,
            'object_type' => null,
            'body' => null,
            'params'=> '[]',
            'target_id'=> '',
            'target_type'=> '',
        );

        $options = array_merge($option,$options);

        $activity =  new ActivityAction();
        $activity->subject_id = $options['subject'];
        $activity->subject_type = $options['subject_type'];
        $activity->object_id = $options['object'];
        $activity->object_type = $options['object_type'];
        $activity->body = $options['body'];
        $activity->type = $options['type'];
        $activity->params = $options['params'];
        $activity->target_id = $options['target_id'];
        $activity->target_type = $options['target_type'];
        $activity->save();

        if($options['type'] == 'friends') {
            $activity               = new ActivityAction();
            $activity->subject_id   = $options['object'];
            $activity->subject_type = $options['object_type'];
            $activity->object_id    = $options['subject'];
            $activity->object_type  = $options['subject_type'];
            $activity->body         = $options['body'];
            $activity->type         = $options['type'];
            $activity->params       = $options['params'];
            $activity->save();
        }


        return $activity->id;
    }
}
