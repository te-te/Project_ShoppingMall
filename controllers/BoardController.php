<?php

class BoardController extends Controller{
    const WRITEFORM  = 'writeform';
    const MODIFYFORM = 'modifyform';

    // 게시판 목록을 생성할 때 사용할 변수
    private $current_page;
    private $total_page;
    private $board_stt;
    private $textTotalCount;

    // 페이지 넘버, 이전, 다음 버튼을 생성할 때 사용할 변수
    private $page_start;
    private $page_last;
    private $before_page;
    private $next_page;

    // *** indexAction() ***
    // 게시판 글 목록 출력
    public function indexAction($params){
        $page = $params['pagenum'];

        $this->getElement($page);
        $this->getElement_pageNum($this->current_page, $this->textTotalCount);

        $index_view = $this->render(array(
            'currentPage' => $this->current_page,
            'totalPage' => $this->total_page,
            'boardStt' => $this->board_stt,
            'textTotalCount' => $this->textTotalCount,
            'pageStart' => $this->page_start,
            'pageLast' => $this->page_last,
            'beforePage' => $this->before_page,
            'nextPage' => $this->next_page
        ));

        return $index_view;
    }

    // *** writeformAction() ***
    // 글쓰기 폼 출력
    public function writeformAction(){
        // 1> 로그인한 상태인지 확인
        if(!$this->_session->isAuthenticated()){
            return $this->redirect('/alert/need_login_error');
        }

        // 2> 글쓰기 화면 렌더링
        $writeform_view = $this->render(array(
            'token' => $this->getToken(self::WRITEFORM))
        );

        return $writeform_view;
    }

    // *** writeAction ***
    // 글쓰기 처리 실행
    public function writeAction(){
        // 1> CSRF 대책의 Token 체크
        $token = $this->_request->getPost('token');

        if(!$this->checkToken(self::WRITEFORM, $token)){
            return $this->redirect('/'.self::WRITEFORM);
        }

        // 2> POST, FILES 전송방식으로 전달 받은 데이터를 변수에 저장
        $postTitle = $this->_request->getPost('post_title');
        $postContent = $this->_request->getPost('post_content');

        $upload_file = $this->_request->getFiles('upload_file');

        // 3> 로그인한 유저의 ID 값 저장
        $user = $this->_session->get('user');
        $id = $user['id'];

        // 4> 글쓰기 처리
        $this->_connect_model->get('Board')->board_write("write", null, $id, $postTitle, $postContent);

        // 5> 마지막에 쓴 글의 board_no 값을 가져옴
        $row = $this->_connect_model->get('Board')->getLastPost();

        $board_no = (int)$row['no'];

        // 6> 업로드 파일이 있을 경우 업로드 처리
        if($upload_file['name'][0] != NULL){
            for($i = 0; $i < count($upload_file['name']); $i++){
                $file_size = $upload_file['size'][$i];
                if($file_size < 2097152){ //첨부 크기 제한
                    $filecode = Date("ymdhis"); // 날짜 (파일명 중복방지용)

                    $file_name = $filecode.$upload_file['name'][$i];
                    $real_name = $upload_file['name'][$i];
                    $file_tmp_name = $upload_file['tmp_name'][$i];

                    $upload_path = './upload/';    // 업로드 경로
                    $total_path = $upload_path.$file_name;

                    // 임시경로의 파일을 옮김
                    move_uploaded_file($file_tmp_name, $total_path);

                    // model에 상품 이미지 등록
                    $this->_connect_model->get('Board')->set_board_file($board_no, $file_name, $real_name);
                }else{
                    return $this->redirect('/alert/size_over_error_w');
                }
            }
        }

        return $this->redirect('/board/page/1');
    }

    // *** modifyformAction() ***
    // 글 수정 폼 출력
    public function modifyformAction($params){
        // 1> 넘어온 매개변수값과 게시글 정보 변수 대입
        $postNum = $params['postnum'];
        $pageNum = $params['pagenum'];

        $boardRow = $this->_connect_model->get('Board')->board_click($postNum);

        // 2> 로그인한 유저의 ID 값과 작성자 비교
        $user = $this->_session->get('user');
        $id = $user['id'];
        if($id != $boardRow['user_id']){
            return $this->redirect('/alert/error');
        }

        // 3> 글 수정 화면 렌더링
        $modifyform_view = $this->render(array(
            'pageNum' => $pageNum,
            'row' => $boardRow,
            'token' => $this->getToken(self::MODIFYFORM)
        ));

        return $modifyform_view;
    }

    // *** modifyAction() ***
    // 글 수정 처리 실행
    public function modifyAction($params){
        // 1> CSRF 대책의 Token 체크
        $token = $this->_request->getPost('token');

        if(!$this->checkToken(self::MODIFYFORM, $token)){
            return $this->redirect('/');
        }

        // 2> 넘어온 매개변수값 변수 대입
        $postNum = $params['postnum'];
        $pageNum = $params['pagenum'];

        // 3> POST, FILES 전송방식으로 전달 받은 데이터를 변수에 저장
        $postTitle = $this->_request->getPost('post_title');
        $postContent = $this->_request->getPost('post_content');

        $upload_file = $this->_request->getFiles('upload_file');

        // 4> 글 수정 처리
        $this->_connect_model->get('Board')->board_write("modify", $postNum, NULL, $postTitle, $postContent);

        // 5> 기존 첨부파일 삭제
        $intPostNum = (int)$postNum;
        $this->_connect_model->get('Board')->del_board_file($intPostNum);

        // 5> 업로드 파일이 있을 경우 업로드 처리
        if($upload_file['name'][0] != NULL){
            for($i = 0; $i < count($upload_file['name']); $i++){
                $file_size = $upload_file['size'][$i];
                if($file_size < 2097152){ //첨부 크기 제한
                    $filecode = Date("ymdhis"); // 날짜 (파일명 중복방지용)

                    $file_name = $filecode.$upload_file['name'][$i];
                    $real_name = $upload_file['name'][$i];
                    $file_tmp_name = $upload_file['tmp_name'][$i];

                    $upload_path = './upload/';    // 업로드 경로
                    $total_path = $upload_path.$file_name;

                    // 임시경로의 파일을 옮김
                    move_uploaded_file($file_tmp_name, $total_path);

                    // model에 상품 이미지 등록
                    $this->_connect_model->get('Board')->set_board_file($postNum, $file_name, $real_name);
                }else{
                    return $this->redirect('/alert/size_over_error_m');
                }
            }
        }

        return $this->redirect('/board/page/'.$pageNum.'/post/'.$postNum);
    }

    // *** viewAction() ***
    // 글 내용 보기 화면 출력
    public function viewAction($params){
        // 1> 넘어온 매개변수값과 게시글 정보 변수 대입
        $postNum = $params['postnum'];
        $pageNum = $params['pagenum'];

        $boardRow = $this->_connect_model->get('Board')->board_click($postNum);
        
        // 2> 첨부파일 정보를 model에서 가져옴
        $fileStt = $this->_connect_model->get('Board')->get_board_file($postNum);

        // 3> 파일 경로가 든 배열을 생성
        $file = array();
        $fileCount = 0;

        while($row = $fileStt->fetch(PDO::FETCH_ASSOC)){
            $file[$fileCount]['file_name'] = $row['file_name'];
            $file[$fileCount]['real_name'] = $row['real_name'];
            $fileCount++;
        }

        // 4> 로그인한 유저의 id값
        $user = $this->_session->get('user');
        $id = $user['id'];

        // 5> 글내용 상세보기 페이지 렌더링
        $view_view = $this->render(array(
            'pageNum' => $pageNum,      // 페이지 넘버
            'row' => $boardRow,         // 게시글 정보
            'id' => $id,                // 로그인 유저 정보
            'file' => $file,            // 첨부파일 정보
            'fileCount' => $fileCount   // 첨부파일 수
        ));

        return $view_view;
    }

    // *** deleteAction() ***
    // 글 삭제 처리 실행
    public function deleteAction($params){
        // 1> 넘어온 매개변수값과 게시글 정보 변수 대입
        $postNum = $params['postnum'];

        $boardRow = $this->_connect_model->get('Board')->board_click($postNum);

        // 2> 로그인한 유저의 ID 값과 작성자 비교
        $user = $this->_session->get('user');
        $id = $user['id'];
        if($id != $boardRow['user_id']){
            return $this->redirect('/alert/error');
        }

        // 3> 글 삭제 처리
        $this->_connect_model->get('Board')->board_delete($postNum);

        return $this->redirect('/board/page/1');
    }

    // *** downloadAction() ***
    // 저장을 위한 header값 수정 메소드
    public function downloadAction($params){
        $filename = $params['filename'];
        $file_dir = "./upload/".$filename;

        header('Content-Type: application/octet-stream');
        header('Content-Length: '.filesize($file_dir));
        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: Base64');

        readfile($file_dir);
    }

    // *** getElement() ***
    // 게시판 목록을 생성할 때 사용할 변수 초기화 메소드
    private function getElement($page){
        // 현재 몇페이지인지 확인
        $this->current_page = $page;

        $scale = 10; // 한 페이지에 표시할 글 수
        $start = ($this->current_page - 1) * $scale; // 페이지 표기를 설정한 시작점

        // 총 게시글 수를 받아옴
        $this->textTotalCount = $this->_connect_model->get('Board')->textTotalCount();

        // 총 페이지수를 구함
        if ($this->textTotalCount % $scale == 0) {
            $this->total_page = floor($this->textTotalCount / $scale);
        } else {
            $this->total_page = floor($this->textTotalCount / $scale) + 1;
        }

        // 한 페이지에 보이는 글의 갯수를 제한한 stt를 받아옴
        $this->board_stt = $this->_connect_model->get('Board')->board_limit($start, $scale);
    }

    // *** getElement_pageNum() ***
    // 게시판 목록에 사용될 페이지 넘버, 이전, 다음 버튼 생성
    private function getElement_pageNum($currentPage, $textTotalCount){
        $this->page_start = floor(($currentPage - 1) / 10) * 10;
        $this->page_last = ceil($textTotalCount / 10);
        $this->before_page = floor($this->page_start);
        $this->next_page = $this->page_start + 11;

        // 페이징 기법
        // 총 페이지 갯수에 따라 첫 페이지, 마지막 페이지를 나누고
        // 첫페이지 일 경우 이전페이지 버튼이 사라지게
        // 마지막 페이지 일 경우 다음 페이지 버튼이 사라지게 만듬
    }
}

?>
