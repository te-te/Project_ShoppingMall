<div id="board_view">
    <div id="view_title"> <!-- 전체적인 타이틀 부분 -->
        <div id="view_title1"> <!-- 제목 밑 날짜 표기 -->
            <label class="view_basic">제목</label>
            <p class="view_basic"><?=$this->escape($row['title'])?></p>
            <p class="view_date"><?=$this->escape($row['date'])?></p>
        </div>

        <div id="view_title2"> <!-- 작성자, 조회수, 추천수 표기 -->
            <label class="view_2">작성자</label>
            <p class="view_2"><?=$this->escape($row['user_id'])?></p>
            <label class="view_2">조회수</label>
            <p class="view_2"><?=$this->escape($row['page_view'])?></p>
        </div>
    </div>

    <div id="view_body"> <!-- 내용 표시구간 -->
        <p class="view_basic"><?=$this->escape($row['contents'])?></p>
    </div>
    <div id="view_file"> <!-- 업로드 된 파일 표시-->
        <p class="view_fileList">첨부파일 </p>
        <?php if($file == NULL): ?>
            첨부파일이 없습니다
        <?php else: ?>
            <?php for($i = 0; $i < $fileCount; $i++): ?>
                <a href="/download/<?=$this->escape($file[$i]['file_name'])?>">
                    <?=$this->escape($file[$i]['real_name'])?>
                </a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>

    <div id="view_footer"> <!-- 버튼 표시구간 -->
        <a href="/board/page/<?=$pageNum?>">목록</a>

        <?php if($id == $row['user_id']): ?>
        <a href="/modifyform/page/<?=$pageNum?>/post/<?=$row['no']?>">수정</a>
        <a href="/delete/<?=$row['no']?>">삭제</a>
        <?php endif; ?>
    </div>
</div>