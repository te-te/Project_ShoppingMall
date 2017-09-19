<div id="c_div_itemInfo">
  <br>
  <div id="info_basic">
    <table>
      <tr>
        <td><img src="<?=$this->escape($img[0])?>"></td>
        <td>
          <ul>
            <li id="itemName"><?=$this->escape($row['name'])?></li>
            <hr>
              <br>
              <li id="itemStock">남은 수량 : <?=$this->escape($row['stock'])?></li>
              <br>
            <br>
            <li id="itemPrice"><?=$this->escape($row['cost'])?> 원</li>
            <br>
            <br>
            <a href="/buyform/<?=$row['no']?>">
              <li id="buyButton">구매하기</li>
            </a>
          </ul>
        </td>
      </tr>
    </table>
  </div>
  <div id="info_detail">
    <div>
      <ul class="tab">
        <li class="selected" id="01"><a href="#01">상품설명</a></li>
        <li class="non_selected"><a href="#02">교환/반품/배송</a></li>
      </ul>
      <ul>
          <?php for($i = 1; $i < $imgCount; $i++): ?>
            <li id="itemContent"><img src="<?=$this->escape($img[$i])?>"></li>
          <?php endfor; ?>
      </ul>
      <br>
      <ul class="tab">
        <li class="non_selected"><a href="#01">상품설명</a></li>
        <li class="selected" id="02"><a href="#02">교환/반품/배송</a></li>
      </ul>
      <ul>
        <li>
          배송비 : 기본배송료는 2,500원 입니다. (도서,산간,오지 일부지역은 배송비가 추가될 수 있습니다)
          50,000원 이상 구매시 무료배송입니다.
        </li>
        <li>
          본 상품의 배송일은 최대 1~5 일입니다.(입금 확인 후) 설치 상품의 경우 다소 늦어질수 있습니다.
          [배송예정일은 주문시점(주문순서)에 따른 유동성이 발생하므로 평균 배송일과는 차이가 발생할 수 있습니다.]
        </li>
        <li>
          상품 청약철회 가능기간은 상품 수령일로 부터 7일 이내 입니다.
        </li>
        <li>
          상품 택(tag)제거 또는 개봉으로 상품 가치 훼손 시에는 7일 이내라도 교환 및 반품이 불가능합니다.
        </li>
        <li>
          저단가 상품, 일부 특가 상품은 고객 변심에 의한 교환, 반품은 고객께서 배송비를 부담하셔야 합니다
          (제품의 하자,배송오류는 제외)
        </li>
        <li>
          일부 상품은 신모델 출시, 부품가격 변동 등 제조사 사정으로 가격이 변동될 수 있습니다.
        </li>
        <li>
          일부 특가 상품의 경우, 인수 후에는 제품 하자나 오배송의 경우를 제외한 고객님의 단순변심에 의한
          교환, 반품이 불가능할 수 있사오니, 각 상품의 상품상세정보를 꼭 참조하십시오.
        </li>
      </ul>
    </div>
  </div>
</div>
