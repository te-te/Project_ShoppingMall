<script>
    function check_input(){
        if(!document.join_form.id.value){
            alert("아이디를 입력하세요.");
            document.join_form.id.focus();
            return;
        }
        if(!document.join_form.pw.value){
            alert("비밀번호를 입력하세요.");
            document.join_form.pw.focus();
            return;
        }
        if(!document.join_form.pwConfirm.value){
            alert("비밀번호 확인을 입력하세요.");
            document.join_form.pwConfirm.focus();
            return;
        }
        if(document.join_form.pw.value !=
            document.join_form.pwConfirm.value){
            alert("비밀번호가 일치하지 않습니다. 다시 입력해주세요.");
            document.join_form.pw.focus();
            document.join_form.pw.select();

            return;
        }
        if(!document.join_form.name.value){
            alert("이름을 입력하세요.");
            document.join_form.name.focus();
            return;
        }
        if(!document.join_form.hp.value){
            alert("휴대폰 번호를 입력하세요.");
            document.join_form.hp.focus();
            return;
        }
        if(!document.join_form.address.value){
            alert("주소를 입력하세요.");
            document.join_form.address.focus();
            return;
        }

        document.join_form.submit();
    }

    function reset_form(){
        document.join_form.id.value = "";
        document.join_form.pw.value = "";
        document.join_form.pwConfirm.value = "";
        document.join_form.name.value = "";
        document.join_form.hp.value = "";
        document.join_form.address.value = "";
    }
</script>
<div id="c_div_join_form">
    <form name="join_form" method="post" action="/account/join">
        <input type="hidden" name="token" value="<?=$this->escape($token)?>"/>
        <?php if(isset($errors) && count($errors) > 0): ?>
            <?php print $this->render('errors', array('errors' => $errors)); ?>
        <?php endif; ?>
        <ul>
            <li>아이디 <input type="text" name="id"></li>
            <li>비밀번호 <input type="password" name="pw"></li>
            <li>비밀번호 확인 <input type="password" name="pwConfirm"></li>
            <li>이름 <input type="text" name="name"></li>
            <li>휴대폰 번호 <input type="text" name="hp"></li>
            <li>주소 <input type="text" name="address"><li>
        </ul>
        <input type="button" value="작성완료" onclick="check_input()">
        <br><br>
        <input type="button" value="Reset" onclick="reset_form()">
    </form>
</div>
