<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\StorageManager;
use App\Repository\Eloquent\ActivityActionRepository;
use App\Events\CreateNotification;

class EncodeAudio extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    protected $file;
    protected $activity_action;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file['file'];
        $this->activity_action = $file['action'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $acObj = new ActivityActionRepository();
        
        $data = $this->file;
        
        $path = \Config::get('constants_activity.STORAGE_PATH');

        $path = $path . 'audios/';

        $tmp_path = $acObj->encodeAudio($path,$data['storage_path']);

        $sm = new StorageManager();

        $audio_path = $sm->getPath($data['user_id'],'audio');
        $f_name = $sm->getFilename('mp3');

        $m_path = $audio_path.$f_name;

        $sm->saveFile($m_path,$tmp_path);
        $sm->deletFile('audios/'.$data['storage_path']);
        @unlink($tmp_path);
        $data['storage_path'] = $data['user_id'].'/'.$f_name;
        $data['parent_type'] = null;

        $audio_id = $acObj->saveFile( $data );
        
        $action = $this->activity_action;
        $action['type']             = 'audio_new';
        $action['object_type']      = 'audio';
        $action['object_id']        = $audio_id;
        $action['attachment_count'] = 1;
        
        $action_id = $acObj->saveActivity($action);

        $attributes = array(

            'resource_type' => \Config::get( 'constants_activity.OBJECT_TYPES.USER.NAME' ),
            'resource_id'   => $action['subject_id'],
            'subject_id'    => $data['user_id'],
            'subject_type'  => 'user',
            'object_id'     => $action_id,
            'object_type'   => \Config::get( 'constants_activity.notification.OBJECT_TYPE.NAME' ),
            'type'          => \Config::get( 'constants_activity.notification.OBJECT_TYPE.TYPES.AUDIO_PROCCESSED' ),
        );

        \Event::fire( new CreateNotification( $attributes ) );
    }
}
