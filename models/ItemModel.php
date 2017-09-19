<?php

class ItemModel extends ExecuteModel{
    // *** setItem() ***
    // 상품 등록
    public function setItem($name, $kind, $stock, $cost){
        $sql =  "INSERT INTO item(name, kind, stock, cost)
                 VALUES('{$name}', '{$kind}', {$stock}, {$cost})";

        $this->execute($sql);
    }

    // *** setItem_img() ***
    // 상품 이미지 등록
    public function setItem_img($itemno, $image_name, $image_path){
        $sql =  "INSERT INTO itemimg(item_no, image_name, image_path)
                 VALUES({$itemno}, '{$image_name}', '{$image_path}')";

        $this->execute($sql);
    }

    // *** getItem() ***
    // 매개변수로 전달받은 상품번호로 상품 정보를 가져옴
    public function getItem($num){
        $sql = "SELECT *
                FROM item
                WHERE no = '{$num}'";
        $row = $this->getRecord($sql);

        return $row;
    }

    // *** getImg_all() ***
    // 모든 아이템 이미지 반환
    public function getImg_all(){
        $sql = "SELECT *
                FROM itemimg";

        $stt = $this->execute($sql);

        return $stt;
    }

    // *** getImg_limit() ***
    // 특정 아이템 이미지 반환
    public function getImg_limit($itemNum){
        $sql = "SELECT *
                FROM itemimg
                WHERE item_no = {$itemNum}";

        $stt = $this->execute($sql);

        return $stt;
    }

    // *** getLastItem() ***
    // 마지막에 만든 상품 정보를 가져옴
    public function getLastItem(){
        $sql = "SELECT * FROM item ORDER BY no DESC LIMIT 1";
        $row = $this->getRecord($sql);

        return $row;
    }

    // *** getList_all() ***
    // 모든 종류의 상품을 반환
    public function getList_all(){
        $sql = "SELECT *
                FROM item";

        $stt = $this->execute($sql);

        return $stt;
    }

    // *** getList_limit() ***
    // 해당 매개변수 종류의 상품을 반환
    public function getList_limit($kind){
        $sql = "SELECT *
                FROM item
                WHERE kind = '{$kind}'";

        $stt = $this->execute($sql);

        return $stt;
    }

    // *** getPurchaseList() ***
    // 유저 고유번호로 구매목록을 조회하여 PDOStatement 반환
    public function getPurchaseList($userID){
        $sql = "SELECT p.p_no, i.image_path, i.name i_name, i.cost, 
                       u.name u_name, p.u_hp, p.u_address
                FROM purchase p, item i, user u
                WHERE p.i_no = i.no AND p.u_id = u.id AND u.id = '{$userID}'";

        $stt = $this->execute($sql);

        return $stt;
    }
}

?>
