<?php
namespace App\Kinnect2Classes;

interface PollsClassInterface {

    public function getAllPolls($user_id);
    public function storePoll($poll,$user_id);
    public function editPoll($id);
    public function updatePoll($id,$view,$comment,$search,$user_id);
    public function showPoll($id,$user_id);
    public function deletePoll($id,$user_id);
    public function pollVotes($option_id,$user_id);
    public function closePoll($id,$user_id);
    public function managePoll($user_id, $api);

}
