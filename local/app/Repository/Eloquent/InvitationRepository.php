<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : local
 * Product Name : PhpStorm
 * Date         : 12-1-16 5:33 PM
 * File Name    : InvitationRepository.php
 */

namespace App\Repository\Eloquent;


use App\Events\CreateNotification;
use App\Invitation;

class InvitationRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function store_invitation($data, $user_id)
    {
        foreach ($data['friends'] as $row) {
            $invitation = new Invitation();
            $invitation->user_id = $user_id;
            $invitation->object_id = $data['object_id'];
            $invitation->object_type = $data['object_type'];
            $invitation->receiver_id = $row;
            $invitation->save();

            $attributes = array(
                'resource_id' => $row,
                'subject_id' => $user_id,
                'object_id' =>  $data['object_id'],
                'object_type' => $data['object_type'],
                'type' => \Config::get('constants_activity.notification.INVITATION.'.strtoupper($data['object_type'])),
            );

            \Event::fire(new CreateNotification($attributes));
        }

        return true;
    }
}
