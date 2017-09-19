<div class="item_regist">
  <form action="/regist" method="post" enctype="multipart/form-data">
      <input type="hidden" name="token" value="<?=$this->escape($token)?>"/>
    <div>
      <table>
        <tr>
          <th><label>상품명</label></th>
          <td><input type="text" name="item_name" id="write_title"></td>
        </tr>
        <tr>
          <th><label>종류</label></th>
          <td>
            <select name="item_kind">
                <option value="doll">인형</option>
                <option value="clothes">옷</option>
                <option value="sundry">잡화</option>
            </select>
          </td>
        </tr>
          <tr>
              <th><label>재고</label></th>
              <td><input type="text" name="item_stock" id="write_title"></td>
          </tr>

          <tr>
              <th><label>가격</label></th>
              <td><input type="text" name="item_cost" id="write_title"></td>
          </tr>
      </table>

      <div id="write_file"> <!-- 파일 업로드를 위한 공간 -->
          상품이미지, 상세정보 이미지
        <input type="file" name="upload_file[]" multiple>
      </div>

      <div id="write_button"> <!-- 작성 버튼 -->
        <input type="submit" value="작성">
        <a href="/account/info">취소</a>
      </div>
    </div>
  </form>
</div>
