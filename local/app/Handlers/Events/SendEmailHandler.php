<?php

namespace App\Handlers\Events;

use App\Events\SendEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
class SendEmailHandler
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
     * @param  SendEmail  $event
     * @return void
     */
    public function handle(SendEmail $event)
    {
        $data = $event->getData();
        $option = array(
            'to' => \Config::get('admin_constants.FEEDBACK_EMAIL'),
            'from' => null,
            'name' => null,
            'message' => null,
            'from_name'=> \Config::get('admin_constants.APP_NAME'),
	        'template' => 'feedback',
            'subject' => 'Feedback!',
        );

        $data= array_merge($option,$data);

        Mail::queue('emails.'.$data['template'], ['data' => $data], function ($m) use ($data) {
            $m->from($data['from'], $data['name']);
            $m->to($data['to'], $data['from_name'])->subject($data['subject']);
        });
    }
}