<?php

class BoardModel extends ExecuteModel{
    // *** textTotalCount() ***
    // 총 게시글 수를 반환
    public function textTotalCount(){
        $sql = "SELECT * FROM board";
        $stt = $this->execute($sql);
        $textTotalCount = $stt->rowCount();

        return $textTotalCount;
    }

    // *** board_limit() ***
    // 한 페이지에 보이는 글의 갯수를 제한한 stt 반환
    public function board_limit($argStart, $argScale){
        $sql = "SELECT * FROM board ORDER BY no DESC LIMIT {$argStart}, {$argScale}";
        $stt = $this->execute($sql);

        return $stt;
    }

    // *** board_click() ***
    // 글번호에 따라 제목과 내용, 조회수 등을 반환
    public function board_click($postNum){
        // 조회수 증가
        $sql = "SELECT page_view FROM board WHERE no = {$postNum}";
        $row = $this->getRecord($sql);
        $hit = $row['page_view'] + 1;
        $sql = "UPDATE board SET page_view = {$hit} WHERE no = {$postNum}";
        $this->execute($sql);

        // 글 정보 반환
        $sql = "SELECT * FROM board WHERE no = {$postNum}";
        $row = $this->getRecord($sql);

        return $row;
    }

    // *** board_write() ***
    // 매개변수로 글수정, 글작성 모드 판별
    // POST 방식으로 받아온 글정보를 게시판 테이블에 입력
    public function board_write($mode, $postNum, $userID, $postTitle, $postContent){
        // 모드에 따라 UPDATE문, SELECT문 구별
        // 게시판 모델 처리
        if($mode == "write"){
          $sql =  "INSERT INTO board(title, contents, user_id, date)
                   VALUES('{$postTitle}', '{$postContent}',
                   '{$userID}', NOW())";
        }
        if($mode == "modify"){
            $sql =  "UPDATE board SET title = '{$postTitle}', contents = '{$postContent}'
                     WHERE no = {$postNum}";
        }

        $this->execute($sql);
    }

    // *** board_delete() ***
    // 글번호 값에 따라 글삭제 처리
    public function board_delete($postNum){
        $sql = "DELETE FROM board WHERE no = {$postNum}";
        $this->execute($sql);
    }

    // *** getLastPost() ***
    // 마지막에 쓴 글 정보를 가져옴
    public function getLastPost(){
        $sql = "SELECT * FROM board ORDER BY no DESC LIMIT 1";
        $row = $this->getRecord($sql);

        return $row;
    }

    // *** set_board_file() ***
    // 매개변수로 글수정, 글작성 모드 판별
    // POST 방식으로 받아온 첨부파일 정보를 첨부파일 테이블에 입력
    // 글번호 값에 맞게 첨부파일 삽입
    public function set_board_file($postNum, $file_name, $real_name){
        $sql =  "INSERT INTO boardfile(board_no, file_name, real_name)
                 VALUES('{$postNum}', '{$file_name}', '{$real_name}')";

        $this->execute($sql);
    }

    // *** del_board_file() ***
    // 글번호 값에 따라 첨부파일 삭제
    public function del_board_file($intPostNum){
        $sql = "DELETE FROM boardfile WHERE board_no = {$intPostNum}";

        $this->execute($sql);
    }

    // *** get_board_file() ***
    // 글번호 값에 따라 첨부파일 반환
    public function get_board_file($postNum){
        $sql = "SELECT * FROM boardfile WHERE board_no = {$postNum}";

        $stt = $this->execute($sql);

        return $stt;
    }
}

?>
