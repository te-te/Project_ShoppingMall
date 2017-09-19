<script>
    function check_input(){
        if(!document.modify_form.post_title.value){
            alert("제목을 입력하세요.");
            document.modify_form.post_title.focus();
            return;
        }
        if(!document.modify_form.post_content.value){
            alert("내용을 입력하세요.");
            document.modify_form.post_content.focus();
            return;
        }

        document.modify_form.submit();
    }

    function reset_form(){
        document.modify_form.post_title.value = "";
        document.modify_form.post_content.value = "";
    }
</script>
<div class="board_write">
  <h2>글쓰기</h2> <!-- 글 작성 내용부 -->
  <form name="modify_form" action="/modify/page/<?=$pageNum?>/post/<?=$row['no']?>" method="post" enctype="multipart/form-data"> <!-- 글작성 php로 이동 후 처리. post방식으로 전송 -->
      <input type="hidden" name="token" value="<?=$this->escape($token)?>"/>
    <div> <!-- 내용 작성 구문 -->
      <table>
        <tr>
          <th><label>제목</label></th>
          <td><input type="text" name="post_title" id="write_name" value="<?=$this->escape($row['title'])?>"></td>
        </tr>
        <tr>
          <th><label>내용</label></th>
          <td>
            <textarea style="resize:none" type="text" name="post_content" id="write_body"><?=$this->escape($row['contents'])?></textarea>'
          </td>
        </tr>
      </table>

      <div id="write_file"> <!-- 파일 업로드를 위한 공간 -->
          <input type="file" name="upload_file[]" multiple>
      </div>

      <div id="write_button"> <!-- 작성 버튼 -->
        <input type="submit" value="작성">
        <a href="/board/page/<?=$pageNum?>/post/<?=$row['no']?>">취소</a>
      </div>
    </div>
  </form>
</div>
