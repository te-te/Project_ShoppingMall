<?php

class ShopController extends Controller{
    const REGISTFORM = 'registform';
    const BUYFORM    = 'buyform';

    // *** indexAction() ***
    // 상품 목록을 표시
    public function indexAction(){
        // 1> 상품 정보, 이미지 정보를 model에 요청하여 가져옴
        $item = $this->_connect_model->get('Item')->getList_all();
        $imgStt = $this->_connect_model->get('Item')->getImg_all();

        $img = array();

        // 2> 이미지 정보가 든 배열을 생성
        while($row = $imgStt->fetch(PDO::FETCH_ASSOC)){
            $total_path = substr($row['image_path'].$row['image_name'], 1);
            $img[$row['item_no']][] = $total_path;
        }

        // 3> 상품 목록 렌더링
        $index_view = $this->render(array(
            'item' => $item, // 상품 정보
            'img' => $img // 이미지 정보
        ), 'itemlist');

        return $index_view;
    }

    // *** itemlistAction() ***
    // 특정 종류의 상품 목록을 표시
    public function itemlistAction($params){
        // 1> 상품 정보, 이미지 정보를 model에 요청하여 가져옴
        $itemKind = $params['kind'];
        $item = $this->_connect_model->get('Item')->getList_limit($itemKind);
        $imgStt = $this->_connect_model->get('Item')->getImg_all();

        $img = array();

        // 2> 이미지 정보가 든 배열을 생성
        while($row = $imgStt->fetch(PDO::FETCH_ASSOC)){
            $total_path = substr($row['image_path'].$row['image_name'], 1);
            $img[$row['item_no']][] = $total_path;
        }

        // 3> 상품 목록 렌더링
        $list_view = $this->render(array(
            'item' => $item, // 상품 정보
            'img' => $img // 이미지 정보
        ));

        return $list_view;
    }

    // *** registformAction() ***
    // 상품 등록 폼 출력
    public function registformAction(){
        // 1> 관리자가 아닌지 체크
        $user = $this->_session->get('user');
        $rating = $user['rating'];
        if($rating != 9){
            return $this->redirect('/alert/error');
        }

        // 2> 상품 등록 화면 렌더링
        $registform_view = $this->render(array(
                'token' => $this->getToken(self::REGISTFORM))
        );

        return $registform_view;
    }

    // *** registAction() ***
    // 상품 등록 처리 실행
    public function registAction(){
        // 1> CSRF 대책의 Token 체크
        $token = $this->_request->getPost('token');

        if(!$this->checkToken(self::REGISTFORM, $token)){
            return $this->redirect('/'.self::REGISTFORM);
        }

        // 2> POST, FILES 전송방식으로 전달 받은 데이터를 변수에 저장
        $item_name = $this->_request->getPost('item_name');
        $item_kind = $this->_request->getPost('item_kind');
        $item_stock = $this->_request->getPost('item_stock');
        $item_cost = $this->_request->getPost('item_cost');

        $upload_file = $this->_request->getFiles('upload_file');

        // 3> 상품 정보 등록
        $this->_connect_model->get('Item')->setItem($item_name, $item_kind,
            $item_stock, $item_cost);

        // 4> 마지막에 등록한 상품의 item_no 값을 가져옴
        $row = $this->_connect_model->get('Item')->getLastItem();

        $item_no = (int)$row['no'];

        // 5> 상품 이미지 등록
        for($i = 0; $i < count($upload_file['name']); $i++){
            $filecode = Date("ymdhis"); // 날짜 (파일명 중복방지용)
            
            $file_name = $filecode.$upload_file['name'][$i];
            $file_tmp_name = $upload_file['tmp_name'][$i];

            $upload_path = './itemimg/';    // 업로드 경로
            $total_path = $upload_path.$file_name;

            // 임시경로의 파일을 옮김
            move_uploaded_file($file_tmp_name, $total_path);

            // model에 상품 이미지 등록
            $this->_connect_model->get('Item')->setItem_img($item_no, $file_name, $upload_path);
        }

        return $this->redirect('/');
    }

    // *** infoAction ***
    // 상품의 상세 정보 출력
    public function infoAction($params){
        // 1> 상품 상세 정보를 model에 요청하여 가져옴
        $itemNum = $params['itemnum'];
        $itemRow = $this->_connect_model->get('Item')->getItem($itemNum);
        $imgStt = $this->_connect_model->get('Item')->getImg_limit($itemNum);

        $img = array();
        $imgCount = $imgStt->rowCount();

        // 2> 이미지 정보가 든 배열을 생성
        while($row = $imgStt->fetch(PDO::FETCH_ASSOC)){
            $total_path = substr($row['image_path'].$row['image_name'], 1);
            $img[] = $total_path;
        }

        // 3> 상품 상세정보 화면 렌더링
        $info_view = $this->render(array(
            'row' => $itemRow,      // 상품 정보
            'img' => $img,          // 이미지 정보
            'imgCount' => $imgCount // 이미지 총 갯수
        ));

        return $info_view;
    }

    // *** buyformAction() ***
    // 상품 구매 폼 출력
    public function buyformAction($params){
        // 1> 로그인한 상태인지 확인
        if(!$this->_session->isAuthenticated()){
            return $this->redirect('/alert/need_login_error');
        }

        // 2> 로그인한 유저의 정보를 변수에 저장
        $user = $this->_session->get('user');
        $id = $user['id'];

        // 3> 유저 정보와 상품 정보를 model에 요청하여 가져옴
        $itemNum = $params['itemnum'];

        $itemRow = $this->_connect_model->get('Item')->getItem($itemNum);
        $imgStt = $this->_connect_model->get('Item')->getImg_limit($itemNum);
        $user = $this->_connect_model->get('User')->getUserInfo($id);

        // 4> 이미지 정보가 든 배열을 생성
        $img = array();

        while($row = $imgStt->fetch(PDO::FETCH_ASSOC)){
            $total_path = substr($row['image_path'].$row['image_name'], 1);
            $img[] = $total_path;
        }

        // 5> 상품 구매 화면 렌더링
        $buy_view = $this->render(array(
            'item' => $itemRow, // 상품 정보
            'img' => $img,      // 이미지 정보
            'user' => $user,    // 유저 정보
            'token' => $this->getToken(self::BUYFORM)
        ));

        return $buy_view;
    }

    // *** buyAction() ***
    // 세션값으로 유저정보를 가져오고, POST값으로 상품정보를 가져옴
    // 가져온 정보를 join해서 구매목록 table에 입력
    public function buyAction($params){
        // 1> CSRF 대책의 Token 체크
        $token = $this->_request->getPost('token');

        if(!$this->checkToken(self::BUYFORM, $token)){
            return $this->redirect('/');
        }

        // 2> 넘어온 매개변수값과 POST값 변수 대입
        $itemNum = $params['itemnum'];
        $buyerHP = $this->_request->getPost('buyerHP');
        $buyerAddress = $this->_request->getPost('buyerAddress');

        // 3> 로그인한 유저의 정보를 변수에 저장
        $user = $this->_session->get('user');
        $id = $user['id'];

        // 4> 각 model에서 유저의 정보와 상품 정보를 가져옴
        $user = $this->_connect_model->get('User')->getUserInfo($id);
        $item = $this->_connect_model->get('Item')->getItem($itemNum);

        // 5> 상품 구매 처리
        // 가격보다 소유한 돈이 적을 시 경고출력
        if($user['cyber_money'] < $item['cost']){
            return $this->redirect('/alert/no_money_error');
        }else{
            // 상품을 사고 남은 돈
            $money = $user['cyber_money'] - $item['cost'];
            $point = $user['cyber_point'] + ($item['cost'] * 0.05);

            // 남은 돈을 입력
            $this->_connect_model->get('User')->modifyCyberMoney($id, $money);

            // 포인트 적립
            $this->_connect_model->get('User')->modifyCyberPoint($id, $point);

            // 구매한 결과를 기록
            $this->_connect_model->get('Purchase')->record($itemNum,
                $id, $buyerHP, $buyerAddress);

            // 구매 성공 메세지 표시, 톱페이지 이동
            return $this->redirect('/alert/buy_complete');
        }
    }
    
    // *** purchaseAction() ***
    // 구매목록 출력
    public function purchaseAction(){
        // 1> 로그인한 상태인지 확인
        if(!$this->_session->isAuthenticated()){
            return $this->redirect('/alert/need_login_error');
        }

        // 2> 로그인한 유저의 정보를 변수에 저장
        $user = $this->_session->get('user');
        $id = $user['id'];

        // 3> 구매목록 stt와 이미지정보를 model에서 가져옴
        $stt = $this->_connect_model->get('Purchase')->getPurchaseList($id);
        $imgStt = $this->_connect_model->get('Item')->getImg_all();

        $rowCount = $stt->rowCount();

        // 4> 이미지 정보가 든 배열을 생성
        $img = array();

        while($row = $imgStt->fetch(PDO::FETCH_ASSOC)){
            $total_path = substr($row['image_path'].$row['image_name'], 1);
            $img[$row['item_no']][] = $total_path;
        }

        // 5> 구매 목록이 없을 경우 에러 표시
        // 있을 경우 구매목록 렌더링
        if($rowCount == 0){
            return $this->redirect('/alert/no_purchase_error');
        }else{
            $purchase_view = $this->render(array(
                'stt' => $stt, // 구매목록 정보
                'img' => $img  // 이미지 정보
            ));

            return $purchase_view;
        }
    }

    // *** alertAction() ***
    // 메세지 표시
    public function alertAction($params){
        $message = "";

        switch($params['message']){
            case "id_input_error":
                $message = "사용자 ID는 영문 1문자 이상 20자 이내로 입력하세요.";
                $location = "/account/joinform";
                break;
            case "overlap_id_error":
                $message = '입력한 ID는 다른 사용자가 사용하고 있습니다.';
                $location = "/account/joinform";
                break;
            case "congratulation":
                $message = "회원가입 축하드립니다!";
                $location = "/";
                break;
            case "need_login_error":
                $message = "로그인이 필요합니다!";
                $location = "/";
                break;
            case "incorrect_error":
                $message = "올바르지 않은 아이디, 비밀번호입니다.";
                $location = "/account/loginform";
                break;
            case "size_over_error_w":
                $message = "용량이 너무 큽니다.";
                $location = "/writeform";
                break;
            case "size_over_error_m":
                $message = "용량이 너무 큽니다.";
                $location = "/board/page/1";
                break;
            case "no_money_error":
                $message = "돈이 부족합니다.";
                $location = "/";
                break;
            case "no_purchase_error":
                $message = "구매하신 상품이 없습니다.";
                $location = "/";
                break;
            case "buy_complete":
                $message = "구매에 성공했습니다.";
                $location = "/";
                break;
            default:
                $message = "잘못된 요청입니다";
                $location = "/";
                break;
        }

        $alert_view = $this->render(array(
            'message' => $message, // 메세지
            'location' => $location // 리다이렉트할 주소
        ));

        return $alert_view;
    }
}

?>