<?php

class PurchaseModel extends ExecuteModel{
    // *** record() ***
    // 구매 결과를 기록
    public function record($itemNum, $id, $buyerHP, $buyerAddress){
        $sql =  "INSERT INTO purchase(i_no, u_id, u_hp, u_address)
                 VALUES({$itemNum}, '{$id}',
                 '{$buyerHP}', '{$buyerAddress}')";

        $result = $this->execute($sql);

        return $result;
    }

    // *** getPurchaseList() ***
    // 유저 고유번호로 구매목록을 조회하여 PDOStatement 반환
    public function getPurchaseList($id){
        $sql = "SELECT p.p_no p_no, i.no i_no, i.name i_name, i.cost, u.name u_name, p.u_hp, p.u_address
                FROM purchase p, item i, user u
                WHERE p.i_no = i.no AND p.u_id = u.id AND u.id = '{$id}'";

        $stt = $this->execute($sql);

        return $stt;
    }
}

?>
