<?php
namespace App\Kinnect2Classes;

interface BattlesClassInterface {

    public function getAllBattles($user_id);
    public function createBattle();
    public function storeBattle($battle,$user_id);
    public function editBattle($id);
    public function updateBattle($id,$view,$comment,$search,$user_id);
    public function showBattle($id,$user_id);
    public function deleteBattle($id,$user_id);
    public function battleVotes($option_id,$user_id);
    public function closeBattle($id,$user_id);
    public function manageBattle($user_id);
}