<?php while($row = $item->fetch(PDO::FETCH_ASSOC)): ?>
    <div class="c_div_itemList">
        <a href="/item/<?=$row['no']?>">
            <img src="<?=$this->escape($img[$row['no']][0])?>">
        </a>
        <ul>
            <li class="itemName"><?=$this->escape($row['name'])?></li>
            <li class="itemPrice">[ <?=$this->escape($row['cost'])?> ]</li>
        </ul>
    </div>
<?php endwhile; ?>