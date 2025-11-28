<?php
namespace App\Models;
use App\Core\DbConnect;

class ActualTeamsModel extends DbConnect {

    public function all(){
        return $this->getConnection()->query("SELECT * FROM actual_teams")->fetchAll();
    }

    public function find($id){
        $stmt = $this->getConnection()->prepare("SELECT * FROM actual_teams WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($team_id,$driver1_id,$driver2_id){
        $stmt = $this->getConnection()->prepare("INSERT INTO actual_teams(team_id,driver1_id,driver2_id) VALUES(?,?,?)");
        return $stmt->execute([$team_id,$driver1_id,$driver2_id]);
    }

    public function update($id,$team_id,$driver1_id,$driver2_id){
        $stmt = $this->getConnection()->prepare("UPDATE actual_teams SET team_id=?,driver1_id=?,driver2_id=? WHERE id=?");
        return $stmt->execute([$team_id,$driver1_id,$driver2_id,$id]);
    }

    public function delete($id){
        $stmt = $this->getConnection()->prepare("DELETE FROM actual_teams WHERE id=?");
        return $stmt->execute([$id]);
    }
}
