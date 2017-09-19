<div id="c_div_myPage">
  <ul>
    <li>아이디</li>
    <li><?=$this->escape($user['id'])?></li>
  </ul>
  <ul>
    <li>이름</li>
    <li><?=$this->escape($user['name'])?></li>
  </ul>
  <ul>
    <li>휴대폰번호</li>
    <li><?=$this->escape($user['hp'])?></li>
  </ul>
  <ul>
    <li>주소</li>
    <li><?=$this->escape($user['address'])?></li>
  </ul>
  <ul>
    <li>보유한 캐쉬</li>
    <li><?=$this->escape($user['cyber_money'])?></li>
  </ul>
  <ul>
    <li>보유한 적립금</li>
    <li><?=$this->escape($user['cyber_point'])?></li>
  </ul>
  <ul>
    <li>등급</li>
    <li><?=$this->escape($user['rating'])?></li>
  </ul>
  <ul>
    <li>가입날짜</li>
    <li><?=$this->escape($user['date'])?></li>
  </ul>
    <?php if($user['rating'] == 9): ?>
        <ul>
            <li><a href="/registform">상품 등록</a></li>
        </ul>
    <?php endif; ?>
    <ul>
        <li><a href="/account/modifyform">정보수정</a></li>
    </ul>
</div>
