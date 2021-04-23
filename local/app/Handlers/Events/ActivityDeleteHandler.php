<?php

namespace App\Handlers\Events;


use App\ActivityAction;
use App\Events\ActivityDelete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityDeleteHandler
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
     * @param  Events  $event
     * @return void
     */
    public function handle(ActivityDelete $activityDelete)
    {
        $options = $activityDelete->get_options();
        $action = ActivityAction::whereSubjectId($options['subject_id'])
                            ->whereObjectId($options['object_id'])
                            ->whereObjectType($options['object_type'])
                            ->first();
        if(!empty($action)){
            if(($action->type == $action->object_type.'_create' || $action->type == $action->object_type.'_new') && $options['subject_id'] == $action->subject_id){
                ActivityAction::where('object_type',$action->object_type)
                    ->where('object_id',$action->object_id)
                    ->delete();
            }
            $action->delete();
        }



    }
}
