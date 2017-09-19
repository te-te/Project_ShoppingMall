<?php

class AccountController extends Controller{
    const JOINFORM   = 'account/joinform';
    const MODIFYFORM = 'account/modifyform';
    const LOGINFORM  = 'account/loginform';

    // *** joinformAction() ***
    // 회원가입 폼을 화면에 출력
    public function joinformAction(){
        $joinform_view = $this->render(array(
            'token' => $this->getToken(self::JOINFORM))
        );

        return $joinform_view;
    }

    // *** joinAction() ***
    // 아이디 중복값 확인 후
    // post값을 user model에 저장
    public function joinAction(){
        // 1> CSRF 대책의 Token 체크
        $token = $this->_request->getPost('token');

        if(!$this->checkToken(self::JOINFORM, $token)){
            return $this->redirect('/'.self::JOINFORM);
        }

        // 2> POST 전송방식으로 전달 받은 데이터를 변수에 저장
        $id = $this->_request->getPost('id');
        $pw = $this->_request->getPost('pw');
        $name = $this->_request->getPost('name');
        $hp = $this->_request->getPost('hp');
        $address = $this->_request->getPost('address');

        // 3> 사용자 ID 체크
        // http://php.net/manual/kr/function.preg-match.php
        if(!preg_match('/^\w{1,20}$/', $id)){
            // ^: 행의 선두를 표시
            // \w : 영문자 1개 문자를 의미
            // {n,m} : 직전의 문자가 n개 이상,m개 이하
            // $ : 행의 종단을 의미
            return $this->redirect('/alert/id_input_error');
        }else if(!$this->_connect_model->get('User')->idCheck($id)){
            // ConnectionModel 의 get()으로 UserModel 클래스 객체생성후 idCheck 호출
            return $this->redirect('/alert/overlap_id_error');
        }

        // 4> 회원 정보 등록
        // UserModel의 join()으로 사용자 정보 등록
        $this->_connect_model->get('User')->join($id, $pw, $name, $hp, $address);
        // 회원가입 축하 메세지 표시, 톱페이지 이동
        return $this->redirect('/alert/congratulation');
    }

    // *** modifyformAction() ***
    // 회원정보 수정 폼을 화면에 출력
    public function modifyformAction(){
        // 1> 로그인한 상태인지 확인
        if(!$this->_session->isAuthenticated()){
            return $this->redirect('/alert/need_login_error');
        }

        // 2> 로그인한 유저의 정보를 변수에 저장
        $user = $this->_session->get('user');
        $id = $user['id'];

        // 3> 회원정보 수정 폼 렌더링
        $modifyform_view = $this->render(array(
            'userID' => $id,
            'token' => $this->getToken(self::MODIFYFORM))
        );

        return $modifyform_view;
    }

    // *** modifyAction() ***
    // post값을 user model에 반영
    public function modifyAction(){
        // 1> CSRF 대책의 Token 체크
        $token = $this->_request->getPost('token');

        if(!$this->checkToken(self::MODIFYFORM, $token)){
            return $this->redirect('/'.self::MODIFYFORM);
        }

        // 2> 로그인한 유저의 정보를 변수에 저장
        $user = $this->_session->get('user');
        $id = $user['id'];

        // 3> POST 전송방식으로 전달 받은 데이터를 변수에 저장
        $pw = $this->_request->getPost('pw');
        $name = $this->_request->getPost('name');
        $hp = $this->_request->getPost('hp');
        $address = $this->_request->getPost('address');

        // 4> 회원 정보 수정
        // UserModel의 modify()으로 사용자 정보 수정
        $this->_connect_model->get('User')->modify($id, $pw, $name, $hp, $address);
        // 유저 정보 페이지로 리다이렉트
        return $this->redirect('/account/info');
    }

    // *** loginformAction() ***
    // 로그인 폼을 화면에 출력
    public function loginformAction(){
        $loginform_view = $this->render(array(
                'token' => $this->getToken(self::LOGINFORM))
        );

        return $loginform_view;
    }

    // *** loginAction() ***
    // model에서 아이디, 비밀번호 일치여부 확인해서 로그인 결과를 반환
    public function loginAction(){
        // 1> CSRF 대책의 Token 체크
        $token = $this->_request->getPost('token');

        if(!$this->checkToken(self::LOGINFORM, $token)){
            return $this->redirect('/'.self::LOGINFORM);
        }

        // 2> POST 전송방식으로 전달 받은 데이터를 변수에 저장
        $id = $this->_request->getPost('userID');
        $pw = $this->_request->getPost('userPW');

        // 3> 아이디와 비밀번호가 모두 올바르면
        // 유저 정보를 가져옴
        $userRow = $this->_connect_model->get('User')->login($id, $pw);

        // 4> 로그인 실패 시 에러메세지 설정
        if($userRow['id'] == NULL){
            return $this->redirect('/alert/incorrect_error');
        }

        // 5> 로그인 처리
        // 로그인 정보가 올바르면 세션으로 사용자 정보를 만듬
        $this->_session->set('user', $userRow);
        // 로그인 상태로 설정
        $this->_session->setAuthenticateStatus(true);

        return $this->redirect('/');
    }

    // *** logoutAction() ***
    public function logoutAction(){
        // 1> 로그인한 상태인지 확인
        if(!$this->_session->isAuthenticated()){
            return $this->redirect('/alert/need_login_error');
        }

        // 2> 세션값 삭제
        $this->_session->clear();

        // 3> 로그아웃 상태로 설정
        $this->_session->setAuthenticateStatus(false);

        return $this->redirect('/');
    }

    public function infoAction(){
        // 1> 로그인한 상태인지 확인
        if(!$this->_session->isAuthenticated()){
            return $this->redirect('/alert/need_login_error');
        }

        // 2> 변수에 로그인한 userID 값 대입
        $user = $this->_session->get('user');
        $id = $user['id'];
        
        // 3> 유저 정보를 model에 요청하여 가져옴
        $user = $this->_connect_model->get('User')->getUserInfo($id);

        // 4> 유저 정보 페이지 렌더링
        $info_view = $this->render(array(
            'user' => $user // 유저 정보
        ));

        return $info_view;
    }
}

?>
