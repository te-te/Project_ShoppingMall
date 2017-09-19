<?php

class UserModel extends ExecuteModel{
    // *** idCheck() ***
    // 아이디 중복 검사 메소드
    public function idCheck($id){
        $sql = "SELECT COUNT(id) as count
                FROM user
                WHERE id = :id";

        $row = $this->getRecord(
            $sql,
            array(':id' => $id)
        );

        if($row['count'] === '0') { // $id가 존재하지 않으면 -> 미등록
            return true;
        }

        return false;
    }

    // *** join() ***
    // 전달받은 값을 사용자 table에 입력
    public function join($id, $pw, $name, $hp, $address){
        $sql =  "INSERT INTO user(id, pw, name, hp, address, date)
                 VALUES('{$id}', 'sha1({$pw})',
                 '{$name}', '{$hp}', '{$address}', NOW())";

        $this->execute($sql);
    }

    // *** modify() ***
    // 전달받은 값으로 로그인한 사용자 정보 수정
    public function modify($id, $pw, $name, $hp, $address){
        $sql = "UPDATE user SET pw = 'sha1({$pw})', name = '{$name}',
                hp = '{$hp}', address = '{$address}'
                WHERE id = '{$id}'";

        $this->execute($sql);
    }

    // *** login() ***
    // 로그인 정보가 올바르면 유저 정보를 가져옴
    public function login($id, $pw){
        $sql =  "SELECT *
                 FROM user WHERE id = '{$id}' AND pw = 'sha1({$pw})'";

        $row = $this->getRecord($sql);

        return $row;
    }

    // *** getUserInfo() ***
    // 로그인 상태의 유저 고유번호를 사용
    // $row는 해당 유저의 정보를 가짐
    public function getUserInfo($id){
        $sql =  "SELECT *
                 FROM user WHERE id = '{$id}'";

        $row = $this->getRecord($sql);

        return $row;
    }

    // *** modifyCyberMoney() ***
    // 유저의 사이버머니 값 수정
    public function modifyCyberMoney($id, $money){
        $sql =  "UPDATE user SET cyber_money = '{$money}'
               WHERE id = '{$id}'";

        $this->execute($sql);
    }

    // *** modifyCyberPoint() ***
    // 유저의 사이버포인트 값 수정
    public function modifyCyberPoint($id, $point){
        $sql =  "UPDATE user SET cyber_point = '{$point}'
               WHERE id = '{$id}'";

        $this->execute($sql);
    }
}

?>
