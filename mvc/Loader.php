<?php

class Loader{
    protected $_directories = array(); // autoload 대상 Directory를 저장하는 property

    // *** regDirectory() ***
    // 인자로 받은 경로를 배열에 저장
    public function regDirectory($dir){
        $this->_directories[] = $dir;
        // array_push($this->_directories, $dir);
        // http://php.net/manual/kr/function.array-push.php
    }

    // *** register() ***
    // spl_autoload_register(): 특정 class의 새로운 객체가 생성되기 전에 실행시킬 메소드를 지정
    //                          해당 class를 자동으로 인자로 가짐
    // 여기서는 먼저 필요한 class 파일을 require하는 requireClsFile 메소드를 사용
    public function register(){
        spl_autoload_register(array($this, 'requireClsFile'));
        // http://php.net/manual/kr/function.spl-autoload-register.php
    }

    // *** requireClsFile() ***
    // 필요한 클래스를 배열 안 경로에서 찾아서
    // 해당 파일이 읽을 수 있는 파일일 경우 require
    public function requireClsFile($class){
        foreach ($this->_directories as $dir){
            $file = $dir.'/'.$class.'.php';
            if(is_readable($file)){
            // http://php.net/manual/kr/function.is-readable.php
                require $file;

                return;
            }
        }
    }
}

?>
