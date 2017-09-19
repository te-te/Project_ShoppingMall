<?php if($session->isAuthenticated()): ?>
    <ul>
        <li><a href="/account/logout">로그아웃</a></li>
        <li>|</li>
        <li><a href="/account/info">내 정보</a></li>
        <li>|</li>
        <li><a href="/purchase">구매목록</a></li>
    </ul>
<?php else: ?>
    <ul>
        <li><a href="/account/loginform">로그인</a></li>
        <li>|</li>
        <li><a href="/account/joinform">회원가입</a></li>
    </ul>
<?php endif; ?>


