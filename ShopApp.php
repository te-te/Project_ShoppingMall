<?php

class ShopApp extends AppBase{
    // *** doDbConnection() ***
    // DB 접속 실행
    protected function doDbConnection(){
        $this->_connectModel->connect('master', // 접속이름
            array(
                'string'    => 'mysql:dbname=shop;host=localhost;charset=utf8', // DB이름 - weblog.localhost
                'user'      => 'root',                                          // DB사용자명
                'password'  => ''                                               // DB사용자의 패스워드
            )
        );
    }

    // *** getRouteDefinition() ***
    // Routiong 정의를 반환
    protected function getRouteDefinition(){
        return array(
            // AccountController 클래스 관련 Routing
            '/account/:action'     => array('controller' => 'account'),

            // ShopController 클래스 관련 Routing
            '/'                    => array('controller' => 'shop', 'action' => 'index'),
            '/registform'          => array('controller' => 'shop', 'action' => 'registform'),
            '/regist'              => array('controller' => 'shop', 'action' => 'regist'),
            '/itemlist/:kind'      => array('controller' => 'shop', 'action' => 'itemlist'),
            '/item/:itemnum'       => array('controller' => 'shop', 'action' => 'info'),
            '/buyform/:itemnum'    => array('controller' => 'shop', 'action' => 'buyform'),
            '/buy/:itemnum'        => array('controller' => 'shop', 'action' => 'buy'),
            '/purchase'            => array('controller' => 'shop', 'action' => 'purchase'),
            '/alert/:message'      => array('controller' => 'shop', 'action' => 'alert'),

            // BoardController 클래스 관련 Routing
            '/board/page/:pagenum' => array('controller' => 'board', 'action' => 'index'),
            '/writeform'           => array('controller' => 'board', 'action' => 'writeform'),
            '/write'               => array('controller' => 'board', 'action' => 'write'),
            '/modifyform/page/:pagenum/post/:postnum'
                                   => array('controller' => 'board', 'action' => 'modifyform'),
            '/modify/page/:pagenum/post/:postnum'
                                   => array('controller' => 'board', 'action' => 'modify'),
            '/board/page/:pagenum/post/:postnum'
                                   => array('controller' => 'board', 'action' => 'view'),
            '/delete/:postnum'     => array('controller' => 'board', 'action' => 'delete'),
            '/download/:filename'  => array('controller' => 'board', 'action' => 'download')
        );
    }
    // Shop APP에서 사용되는 Controller, Action
    // Contorller - Action      - path 정보                      - 내용

    // account    - joinform    - /account/:action              - 회원가입 form
    // account    - join        - /account/:action              - 회원가입 동작 실행
    // account    - modifyform  - /account/:action              - 회원 정보수정 form
    // account    - modify      - /account/:action              - 회원 정보수정 동작 실행
    // account    - loginform   - /account/:action              - 로그인 form
    // account    - login       - /account/:action              - 로그인 동작 실행
    // account    - logout      - /account/:action              - 로그아웃 동작 실행
    // account    - info        - /account/:action              - 회원정보 보기

    // shop       - index       - /                             - 쇼핑몰의 메인 페이지 (모든 상품 표시)
    // shop       - registform  - /registform                   - 상품등록 폼
    // shop       - regist      - /regist                       - 상품등록
    // shop       - itemlist    - /itemlist/:kind               - 특정 종류의 상품 목록
    // shop       - info        - /item/:itemnum                - 상품 상세보기
    // shop       - buyform     - /buyform/:itemnum             - 상품구매 폼
    // shop       - buy         - /buy/:itemnum                 - 상품구매
    // shop       - purchase    - /purchase                     - 구매목록
    // shop       - alert       - /alert/:message               - 메세지 표시

    // board      - index       - /board/page/:pagenum          - 게시판 글 목록
    // board      - writeform   - /writeform                    - 글 작성 폼
    // board      - write       - /write                        - 글 작성
    // board      - modifyform  - /modifyform/page/:pagenum/post/:postnum
    //                                                          - 글 수정 폼
    // board      - modify      - /modify/page/:pagenum/post/:postnum
    //                                                          - 글 수정
    // board      - view        - /board/page/:pagenum/post/:postnum
    //                                                          - 글 상세보기
    // board      - delete      - /delete/:postnum              - 글 삭제
    // board      - download    - /download/:filename           - 파일 다운로드

    // *** getRootDirectory() ***
    // Root Directory 경로를 반환
    public function getRootDirectory(){
        return dirname(__FILE__); // ShopApp.php가 저장되어 있는 디렉토리

        // http://php.net/menual/en/function.dirname.php
    }
}

?>
