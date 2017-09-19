<div id="purchaseList">
    <table border="1px">
        <tr>
            <th>주문번호</th>
            <th>이미지</th>
            <th>상품명</th>
            <th>가격</th>
            <th>주문자</th>
            <th>연락처</th>
            <th>배송지</th>
        </tr>
        <?php while($row = $stt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
          <td><?=$this->escape($row['p_no'])?></td>
          <td><img src="<?=$this->escape($img[$row['i_no']][0])?>" width="150px" height="150px"></td>
          <td><?=$this->escape($row['i_name'])?></td>
          <td><?=$this->escape($row['cost'])?></td>
          <td><?=$this->escape($row['u_name'])?></td>
          <td><?=$this->escape($row['u_hp'])?></td>
          <td><?=$this->escape($row['u_address'])?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
