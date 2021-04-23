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

class EncodeVideo extends Job implements SelfHandling, ShouldQueue
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
        $data  = $this->file;

        $sm = new StorageManager();
        $acObj = new ActivityActionRepository();

        $path = \Config::get('constants_activity.STORAGE_PATH');

        $path = $path . '/videos/';
        $storage_path = $data['storage_path'];
        $tmp = $acObj->encodeVideo($path,$data['storage_path']);

        $temp_video = public_path('storage/').$tmp['video'];
        $path = $sm->getPath($data['user_id'],'video');

        $video_name = $sm->getFilename('mp4');
        $path = $path.$video_name;
        $sm->saveFile($path,$temp_video);
        $sm->deletFile('videos/'.$storage_path);
        @unlink($temp_video);

        $data['storage_path'] = $data['user_id'].'/'.$video_name;
        $data['mime_type'] = 'video/mp4';

        $file = $acObj->saveFile( $data,TRUE );

        if ( @$file->file_id ) {
            $temp['title'] = $data['name'];
            $temp['file_id']    = $file->file_id;
            $temp['owner_type'] = 'user';
            $temp['owner_id']   = $data['user_id'];
            $temp['album_id']   = 0;
            $temp['code']       = $data['extension'];

            $video_id = $acObj->saveActivityVideo( $temp );

            $file->parent_id = $video_id;
            $file->save();

            $image = public_path('storage/').$tmp['image'];
            if(file_exists($image)) {
                $photo = $sm->saveVideoThumbnail($data['user_id'], $image, $video_id);
                $acObj->saveFile($photo);
                @unlink(public_path('storage/').$tmp['image']);
            }
			$action = $this->activity_action;
            $action['type']             = 'video_new';
            $action['object_type']      = 'video';
            $action['object_id']        = $video_id;
            $action['attachment_count'] = 1;

            $action_id = $acObj->saveActivity($action);

            $attributes = array(

                'resource_type' => \Config::get( 'constants_activity.OBJECT_TYPES.USER.NAME' ),
                'resource_id'   => $action['subject_id'],
                'subject_id'    => $data['user_id'],
                'subject_type'  => 'user',
                'object_id'     => $action_id,
                'object_type'   => \Config::get( 'constants_activity.notification.OBJECT_TYPE.NAME' ),
                'type'          => \Config::get( 'constants_activity.notification.OBJECT_TYPE.TYPES.VIDEO_PROCCESSED' ),
            );

            \Event::fire( new CreateNotification( $attributes ) );
        }
    }
}
