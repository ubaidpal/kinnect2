<?php
namespace App\Kinnect2Classes;


use App\AuthorizationAllow;
use App\Events\ActivityDelete;
use App\Events\ActivityLog;
use App\Facades\AuthorizationAllowClassFacade;
use App\Repository\Eloquent\ActivityActionRepository;
use App\Services\StorageManager;
use App\StorageFile;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\PollOption;
use Carbon\Carbon;
use App\PollVote;
use App\Poll;


class PollsClass implements PollsClassInterface
{

    public function __construct()
    {
        $this->activity_type = \Config::get('constants_activity.OBJECT_TYPES.POLLS.NAME');
    }

    public function getAllPolls($user_id,$query = [])
    {
        $users_poll = $this->get_users_polls($user_id,$query);
        $poll = $this->get_polls($user_id,$query);
        $data['user_poll'] = $users_poll;
        $data['poll'] = $poll;

        return ($data);
    }

    public function get_users_polls($user_id,$query = [])
    {
        $queryObj = Poll::whereUserId($user_id)
                        ->orderBy('created_at', 'desc')
                        ->take(10);
        if(!empty($query['title'])){
            $queryObj->where('title','LIKE',"%{$query['title']}%");
        }

        return $queryObj->get();
    }

    public function get_polls($user_id,$query = [])
    {
        $queryObj = Poll::where('user_id', '<>', $user_id)
                            ->orderBy('created_at', 'desc')
                            ->take(10);
        if(!empty($query['title'])){
            $queryObj->where('title','LIKE',"%{$query['title']}%");
        }

        return $queryObj->get();
    }

    public function get_all_polls()
    {
        return Poll::orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    public function get_poll_details($poll, $detail = false, $user_id = null)
    {
        if (is_object($poll)) {
           // $poll = (array) $poll;
        }


        $user = User::find($poll->user_id);
        if (!empty($user)) {
            $poll->creator_name = $user->displayname;
            $poll->creator_url = $user->username;
            $poll->is_voted = $this->poll_votes($poll->id, $user_id);
            $poll->profile_photo_url = \Kinnect2::getPhotoUrl($user->photo_id, $poll->user_id, 'user', 'thumb_normal');
        } else {
            $poll->creator_name = '';
            $poll->creator_url = '';
            $poll->is_voted = '';
        }
        if ($detail) {
            $poll->options = $this->poll_options($poll->id);
            if ($user_id) {
                $poll->is_voted = $this->poll_votes($poll->id, $user_id);
            }
        }
        $poll->privacy = $this->_get_privacy($poll->id);
        return $poll;
    }
    private function _get_privacy($id)
    {
        $privacy = AuthorizationAllow::whereResourceId($id)->whereResourceType('poll')->lists('permission', 'action');
        $privacyR['auth_allow_view'] = '';
        $privacyR['auth_allow_comment']  = '';
        if (!empty($privacy)) {
            if (isset($privacy['view'])) {
                $privacyR['auth_allow_view'] = \Config::get('constants.PERMISSION.' . $privacy['view']);
            }
            if (isset($privacy['comment'])) {
                $privacyR['auth_allow_comment'] = \Config::get('constants.PERMISSION.' . $privacy['comment']);
            }

        }


        return $privacyR;

    }
    public function poll_options($id)
    {
        return PollOption::wherePollId($id)->get();
    }

    public function poll_votes($id, $user_id)
    {
        $votes = DB::table('poll_votes')->where('poll_id', $id)->where('user_id', $user_id)->first();
        if (!empty($votes)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    /**
     * @param $poll
     * @param $user_id
     */
    public function storePoll($poll, $user_id)
    {
        $poll->starttime = Carbon::now();
        $poll->user_id = $user_id;
        $poll->save();
        $i = 0;
        foreach (Input::get('poll_option') as $test) {
            $i++;
        }
        $i = $i - 1;
        $smObj = new StorageManager();
        $aarObj = new ActivityActionRepository();
        while ($i >= 0) {
            $option = new PollOption();
            $option->poll_option = Input::get('poll_option')[$i];
            $option->poll_id = $poll->id;
            $option->save();
            if(Input::hasFile('poll_option_file.'.$i)){
                $photo = $smObj->storeFile($user_id,Input::file('poll_option_file.'.$i),'poll_photo',null,'poll_photo');
                $photo['parent_type'] = 'poll_option';
                $photo['parent_id'] = $option->id;
                $file_id = $aarObj->saveFile($photo);
                $this->resizePhoto($file_id,$user_id,$option->id);
            }
            $i = $i - 1;
        }
        $view = (Input::get('auth_allow_view'));
        $comment = (Input::get('auth_allow_comment'));
        \AuthorizationAllowClassFacade::Setting('poll', $poll->id, $view, 'view');
        \AuthorizationAllowClassFacade::Setting('poll', $poll->id, $comment, 'comment');

        $options = array(
            'object_type' => $this->activity_type,
            'type' => \Config::get('constants_activity.OBJECT_TYPES.POLLS.ACTIONS.CREATE'),
            'subject' => $user_id,
            'subject_type' => \Config::get('constants_activity.OBJECT_TYPES.USER.NAME'),
            'object' => $poll->id,

        );

        \Event::fire(new ActivityLog($options));
    }
    protected function resizePhoto($file_id,$user_id,$option_id){
        $file = StorageFile::where('file_id',$file_id)->first();
        if(!empty($file->file_id)){
            $aarObj = new ActivityActionRepository();
            $popup_width = \Config::get('constants.POLL_THUMB_WIDTH');
            $popup_height = \Config::get('constants.POLL_THUMB_HEIGHT');;

            $sm = new StorageManager();
            $photo = $sm->getFileByPath('photos/polls/'.$file->storage_path);
            $img = \Image::make($photo);

            $width = $img->width();
            $height = $img->height();

            if($width > $popup_width || $height > $popup_height) {
                if($width >= $height) {
                    $img = $img->resize($popup_width,null,function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }else{
                    $img = $img->resize(null,$popup_height,function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }

            $encoded = $img->encode('jpg',90);
            $string = $encoded->__toString();
            $file_name = $sm->getFilename('jpg');
            $path = $user_id.'/'.$file_name;
            $sm->saveFile('photos/polls/thumbs/'.$path,$string,1);

            $data['type'] = 'poll_thumb';
            $data['parent_type'] = 'poll_option';
            $data['parent_id'] = $option_id;
            $data['parent_file_id'] = $file->file_id;
            $data['user_id'] = $user_id;
            $data['storage_path'] = $path;
            $data['extension'] = 'jpg';
            $data['mime_type'] = 'image/jpeg';
            $data['name'] = $file->name;
            $data['size'] = $img->filesize();
            $data['width'] = $img->width();
            $data['height'] = $img->height();
            $data['hash'] = hash('sha256',$img);
            $data['is_temp'] = 0;
            $aarObj->saveFile($data);
            $img->destroy();
        }
    }

    public function editPoll($id)
    {
        $polls = Poll::findorFail($id);

        return ($polls);
    }

    public function updatePoll($id, $view, $comment, $search, $user_id)
    {

        $poll = Poll::findOrFail($id);
        if ($user_id == $poll->user_id) {
            $poll->search = $search;
            $poll->save();
            \AuthorizationAllowClassFacade::changeSetting('poll', $poll->id, $view, 'view');
            \AuthorizationAllowClassFacade::changeSetting('poll', $poll->id, $comment, 'comment');
        }
    }

    public function showPoll($id, $user_id)
    {

        $poll = Poll::find($id);
        if ($poll) {
            $options = $this->poll_options($id);
            $votes = $this->poll_votes($id, $user_id);
            $data['poll'] = $poll;
            $data['options'] = $options;
            $data['votes'] = $votes;

            return ($data);
        } else {
            return false;
        }

    }

    public function get_users()
    {
        return User::all();
    }

    public function deletePoll($id, $user_id)
    {

        $poll = Poll::findOrFail($id);
        if ($user_id == $poll->user_id) {
            AuthorizationAllowClassFacade::deleteResource($id, 'poll');
            $options = $this->poll_options($id);
            $votes = $this->poll_all_votes($id);
            $poll->delete();
            foreach ($options as $option) {
                $option->delete();
            }
            foreach ($votes as $vote) {
                $vote->delete();
            }
            $params = [
                'subject_id' => $user_id,
                'object_id' => $id,
                'object_type' => \Config::get('constants_activity.OBJECT_TYPES.POLLS.NAME')
            ];

            \Event::fire(new ActivityDelete($params));

        }

    }

    public function poll_all_votes($id)
    {
        return PollVote::wherePollId($id)->get();
    }

    public function pollVotes($option_id, $user_id)
    {
        if (empty($option_id) || empty($user_id)) {
            return FALSE;
        }
        $option = PollOption::find($option_id);
        if (empty($option->id)) {
            return FALSE;
        }
        if (!is_null($option)) {
           $poll = Poll::find($option->poll_id);
            if (!is_null($poll)) {
               $checkVote = PollVote::where('poll_id', $poll->id)->where('user_id', $user_id)->first();
                $poll->vote_count++;
                $option->votes++;
                if ($poll->is_closed == 0) {
                    if ($checkVote == NULL) {
                        if ($poll->user_id != $user_id) {
                            $option->save();
                            $poll->save();
                            PollVote::create(['poll_id' => $poll->id,
                                'user_id' => $user_id,
                                'poll_option_id' => $option_id
                            ]);

//                    $options = array(
//                        'object_type' => $this->activity_type,
//                        'type' => \Config::get('constants_activity.OBJECT_TYPES.POLLS.ACTIONS.VOTE'),
//                        'subject' => $user_id,
//                        'object' => $poll->id,
//
//                    );

                            // \Event::fire(new ActivityLog($options));
                        }else{
                            return \Api::other_error('You are the owner of this poll');
                        }
                    }
                }
                return true;
            }
        }
        return false;
    }

    public function closePoll($id, $user_id)
    {
        $poll = Poll::findOrNew($id);

        if ($user_id == $poll->user_id) {
            $polls = $poll->check_closed;
            $poll->is_closed = $polls;
            if ($poll->is_closed == 1) {
                $poll->endtime = Carbon::now();
            } else {
                $poll->endtime = 0;
            }
            $poll->save();

//            $options = array(
//                'object_type' => $this->activity_type,
//                'type' => \Config::get('constants_activity.OBJECT_TYPES.POLLS.ACTIONS.CLOSE'),
//                'subject' => $user_id,
//                'object' => $id,
//
//            );
//
//            \Event::fire(new ActivityLog($options));
        }
    }

    public function managePoll($user_id, $api,$query = [])
    {
        $l = (Config::get('constants.DISPLAY_LIMIT'));

        $poll = DB::table('polls')->where('user_id', $user_id)->orderBy('created_at', 'desc');

        if(!empty($query['title'])){
            $poll->where('title','LIKE',"%{$query['title']}%");
        }

        if ($api) {
            return $poll->get();
        } else {
            return $poll->paginate($l);
        }

    }

}
