<div id="c_div_buyForm">
  <form action="/buy/<?=$this->escape($item['no'])?>" method="post">
      <input type="hidden" name="token" value="<?=$this->escape($token)?>"/>
    <center>
    <br>
    <table>
      <tr>
        <td colspan="2"><img src="<?=$this->escape($img[0])?>"></td>
      </tr>
      <tr>
        <td><labe>주문자 </labe></td>
        <td><?=$user['name']?></td>
      </tr>
      <tr>
        <td><labe>휴대폰 번호 </labe></td>
        <td><input type="text" value="<?=$this->escape($user['hp'])?>" name="buyerHP"></td>
      </tr>
      <tr>
        <td><labe>배송지 </labe></td>
        <td><input type="text" value="<?=$this->escape($user['address'])?>" name="buyerAddress"></td>
      </tr>
      <tr>
        <td><labe>소지 캐쉬 </labe></td>
        <td><?=$this->escape($user['cyber_money'])?></td>
      </tr>
      <tr>
        <td><labe>소지 포인트 </labe></td>
        <td><?=$this->escape($user['cyber_point'])?></td>
      </tr>
      <tr>
        <td><labe>주문하시는 상품 </labe></td>
        <td><?=$this->escape($item['name'])?></td>
      </tr>
      <tr>
        <td><labe>상품 가격 </labe></td>
        <td><?=$this->escape($item['cost'])?></td>
      </tr>
      <tr>
        <td><labe>적립 포인트 </labe></td>
        <td><?=$this->escape($item['cost'] * 0.05)?></td>
      </tr>
    </table>
    <input type="submit" value="주문하기">
    </center>
  </form>
</div>
