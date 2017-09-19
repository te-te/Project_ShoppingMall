<div id="loginForm">
    <form action="/account/login" method="post">
        <input type="hidden" name="token" value="<?=$this->escape($token)?>"/>
        <?php if(isset($errors) && count($errors) > 0): ?>
            <?php print $this->render('errors', array('errors' => $errors)); ?>
        <?php endif; ?>
        <table>
            <tr>
                <img src="/img/login.jpg">
            </tr>
            <tr>
                <td>ID<input id="input_id" type="text" name="userID"></td>
                <td rowspan="2">
                    <input id="input_submit" type="submit" value="로그인">
                </td>
            </tr>
            <tr>
                <td>PW<input id="input_pw" type="password" name="userPW"></td>
            </tr>
            <tr>
                <td>
                    <br>
                    <li><a href="/account/joinform">회원가입</a></li>
                </td>
            </tr>
        </table>
  </form>
</div> <!-- end of h_div_loginForm -->
