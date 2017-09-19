<?php

// 계승하여 사용할 것을 전제로 하는 클래스
// abstract 클래스로 정의
abstract class AppBase{
    protected $_connectModel;   // ConnectModel 클래스의 인스턴스를 저장하는 프로파티
    protected $_request;        // Request 클래스의 인스턴스를 저정하는 프로파티
    protected $_response;       // Response 클래스의 인스턴스를 저정하는 프로파티
    protected $_router;         // Router 클래스의 인스턴스를 저장하는 프로파티
    protected $_session;        // Session 클래스의 인스턴스를 저장하는 프로파티
    protected $_displayErrors;  // 에러 표시 온/오프를 저장하기 위한 프로파티
    protected $_signinAction
                = array();      // 로그인 하였을 때의 컨트롤러와 액션의 조합을 저장하는 배열 프로파티

    const CONTROLLER     = 'Controller';     // Controller명의 접미사
    const CONTROLLERSDIR = '/controllers';   // controllers 폴더
    const MODELSDIR      = '/models';        // models 폴더
    const WEBDIR         = '/mvc_htdocs';    // 도큐먼트 루트의 폴더
    const VIEWSDIR       = '/views';         // views 폴더

    // *** 생성자 __construct() ***
    public function __construct($dspErr){
        $this->initialize();
        $this->setDisplayErrors($dspErr);
        $this->doDbConnection();
    }

    // *** initialize() ***
    protected function initialize(){
        $this->_connectModel = new ConnectModel();
        $this->_request      = new Request();
        $this->_response     = new Response();
        $this->_router       = new Router($this->getRouteDefinition());
        $this->_session      = new Session();
    }

    // *** setDisplayErrors() ***
    // 에러표시모드를 설정, true - 에러표시 , false - 비표시
    protected function setDisplayErrors($dspErr){
        if($dspErr){
            $this->_displayErrors = true;
            ini_set('display_errors', 1);
            ini_set('error_reporting', E_ALL);
        }else{
            $this->_displayErrors = false;
            ini_set('display_errors', 0);
        }
        // http://php.net/manual/kr/function.ini-set.php
        // http://php.net/manual/kr/function.ini-get.php
        // http://php.net/manual/kr/function.error-reporting.php
        // php.ini의 설정을 php소스코드에서 제어
    }

    // *** isDisplayErrors() ***
    // 에러표시모드가 표시모드(true)인지 비표시모드(false)인지를 반환
    public function isDisplayErrors(){
        return $this->_displayErrors;
    }

    // *** run() ***
    // 리퀘스트된 URL을 이용해 Routing하여 Controller + Action을 획득
    // Controller + Action을 Dispatch(일의 순서를 정해 실행시킴, getContent()
    // 내부에서 수행)하여 ActionMethod를 실행
    // Response객체에 저장되는 Rendering되는 Data를 Client로 send
    public function run(){
        try{
            $parameters = $this->_router->getRouteParams($this->_request->getPath());

            if($parameters === false){
                throw new FileNotFoundException('NO ROUTE'.$this->_request->getPath());
            }

            $controller = $parameters['controller'];
            $action     = $parameters['action'];

            $this->getContent($controller, $action, $parameters);

        }catch(FileNotFoundException $e){
            $this->dispErrorPage($e);

        }catch(AuthorizedException $e){
            list($controller, $action) = $this->_signinAction;
            $this->getContent($controller, $action);
        }

        $this->_response->send();

        // http://php.net/manual/kr/function.list.php
    }

    // *** getContent() ***
    // 컨트롤러명을 구해내어 컨트롤러 클래스의 인스탄스를 생성
    // 생성된 컨트롤러 클래스 인스탄스에 액션 실행을 의뢰
    // 액션의 실행(엑션메소드)의 반환값(Contents-Response화면)을 Response의 객체에 설정
    public function getContent($controllerName, $action, $parameters = array()){
        $controllerClass = ucfirst($controllerName).self::CONTROLLER;
        $controller = $this->getControllerObject($controllerClass);

        if($controller === false){
            throw new FileNotFoundException($controllerClass.'NOT FOUND.');
        }

        $content = $controller->dispatch($action, $parameters);

        $this->_response->setContent($content);

        // http://php/net/manual/kr/function.ucfirst.php
        // (첫글자 대문자로 변환)
    }

    // *** getControllerObject() ***
    // Controller클래스를 검색하여 인스탄스화 하여 이것을 반환값으로 반환
    protected function getControllerObject($controllerClass){
        if(!class_exists($controllerClass)){
            $controllerFile = $this->getControllerDirectory().'/'.$controllerClass.'.php';

            if(!is_readable($controllerFile)){
                return false;
            }
            else {
                require_once $controllerFile;

                if (!class_exists($controllerClass)){
                    return false;
                }
            }
        }

        $controller = new $controllerClass($this);

        return $controller;

        // http://php.net/manual/kr/function.class-exists.php
        // http://php.net/manual/kr/function.is-readable.php
    }

    // *** dispErrorPage() ***
    // 404 Error페이지
    protected function dispErrorPage($e){
        $this->_response->setStatusCode(404, 'FILE NOT FOUND.');
        $errMessage = $this->isDisplayErrors() ? $e->getMessage() : 'FILE NOT FOUND.';
        $errMessage = htmlspecialchars($errMessage, ENT_QUOTES, 'UTF-8');
        $html = "
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<title> HTTP 404 Error </title>
</head>
<body>
{$errMessage}
</body>
</html>
";
        $this->_response->setContent($html);

        // http://php.net/manual/kr/function.htmlspecialchars.php
        // (특수문자를 HTML Entity로 변환)
    }

    // *** getRouteDefinition() ***
    // 정의된 Routing정보를 반환
    abstract protected function getRouteDefinition();

    // *** doDbConnection() ***
    protected function doDbConnection(){}

    // *** getConnectModelObject() ***
    public function getConnectModelObject(){
        return $this->_connectModel;
    }

    // *** getRequestObject() ***
    public function getRequestObject(){
        return $this->_request;
    }

    // *** getResponseObject() ***
    public function getResponseObject(){
        return $this->_response;
    }

    // *** getSessionObject() ***
    public function getSessionObject(){
        return $this->_session;
    }

    // *** getRootDirectory() ***
    abstract public function getRootDirectory();

    // *** getControllerDirectory() ***
    public function getControllerDirectory(){
        return $this->getRootDirectory().self::CONTROLLERSDIR;
    }

    // *** getModelDirectory() ***
    public function getModelDirectory(){
        return $this->getRootDirectory().self::MODELSDIR;
    }

    // *** getViewDirectory() ***
    public function getViewDirectory(){
        return $this->getRootDirectory().self::VIEWSDIR;
    }

    // *** getDocDirectory() ***
    public function getDocDirectory(){
        return $this->getRootDirectory().self::WEBDIR;
    }
}

?>
