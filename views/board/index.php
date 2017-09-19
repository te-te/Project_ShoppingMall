<div id="board_wrap">
    <div id="board_main">
        <h1>자유게시판</h1> <!-- 방명록 타이틀 -->
        <table>
            <thead> <!-- 게시판 상단 -->
            <tr>
                <th width="70px">번호</th>
                <th width="600px">제목</th>
                <th width="100px">글쓴이</th>
                <th width="100px">날짜</th>
                <th width="70px">조회수</th>
            </tr>
            </thead>
            <tbody> <!-- 게시판 본체 -->
            <?php while($row = $boardStt->fetch()): ?>
                <tr>
                  <td><?=$this->escape($row['no'])?></td>
                  <td><a href="/board/page/<?=$currentPage?>/post/<?=$this->escape($row['no'])?>">
                          <?=$this->escape($row['title'])?>
                      </a>
                  </td>
                  <td><?=$this->escape($row['user_id'])?></td>
                  <td><?=$this->escape($row['date'])?></td>
                  <td><?=$this->escape($row['page_view'])?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div> <!-- end of board_main -->
</div> <!-- end of board_wrap -->
<div id="page_num"> <!-- 페이지 넘버 -->
<?php
    if($pageStart > 1){
    echo "<a href='/board/page/{$beforePage}'> ◀ 이전 </a>";
    }

    for($i = $pageStart + 1; $i <= $pageStart + 10; $i++){
    if($currentPage == $i){
    echo "<b> [{$i}] </b>";
    }else if($i <= $pageLast){
    echo "<a href='/board/page/{$i}'> [{$i}] </a>";
    }
    }

    if($pageStart + 10 < $pageLast){
    echo "<a href='/board/page/{$nextPage}'> 다음 ▶ </a>";
    }
?>
</div>
<div id="board_button"> <!-- 버튼창 -->
<?php if($session->isAuthenticated()): ?>
    <div id='button'><a href='/writeform'>글쓰기</a></div>
<?php endif; ?>
</div>


