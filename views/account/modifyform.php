<script>
    function check_input(){
        if(!document.modify_form.pw.value){
            alert("비밀번호를 입력하세요.");
            document.modify_form.pw.focus();
            return;
        }
        if(!document.modify_form.pwConfirm.value){
            alert("비밀번호 확인을 입력하세요.");
            document.modify_form.pwConfirm.focus();
            return;
        }
        if(document.modify_form.pw.value !=
            document.modify_form.pwConfirm.value){
            alert("비밀번호가 일치하지 않습니다. 다시 입력해주세요.");
            document.modify_form.pw.focus();
            document.modify_form.pw.select();

            return;
        }
        if(!document.modify_form.name.value){
            alert("이름을 입력하세요.");
            document.modify_form.name.focus();
            return;
        }
        if(!document.modify_form.hp.value){
            alert("휴대폰 번호를 입력하세요.");
            document.modify_form.hp.focus();
            return;
        }
        if(!document.modify_form.address.value){
            alert("주소를 입력하세요.");
            document.modify_form.address.focus();
            return;
        }

        document.modify_form.submit();
    }

    function reset_form(){
        document.modify_form.pw.value = "";
        document.modify_form.pwConfirm.value = "";
        document.modify_form.name.value = "";
        document.modify_form.hp.value = "";
        document.modify_form.address.value = "";
    }
</script>
<form name="modify_form" method="post" action="/account/modify">
    <input type="hidden" name="token" value="<?=$this->escape($token)?>"/>
    <ul>
        <li>아이디 <?=$this->escape($userID)?></li>
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
