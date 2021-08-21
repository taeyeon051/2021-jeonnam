<?php

namespace Kty\Controller;

use Kty\App\DB;
use Kty\Library\Lib;

class UserController extends MasterController
{
    public function login()
    {
        $this->render('login');
    }

    public function loginProcess()
    {
        $id = $_POST['userId'];
        $pwd = $_POST['userPwd'];

        if (trim($id) === "" || trim($pwd) === "") return Lib::msgAndBack("빈 값이 있습니다.");

        $sql = "SELECT * FROM users WHERE id = ? AND pw = ?";
        $user = DB::fetch($sql, [$id, $pwd]);

        if ($user) {
            Lib::msgAndGo('로그인 되었습니다.', '/');
            $_SESSION['user'] = $user;
        } else Lib::msgAndBack("아이디 혹은 비밀번호를 다시 확인해주세요.");
    }

    public function logout()
    {
        unset($_SESSION['user']);
        Lib::msgAndGo('로그아웃 되었습니다.', '/');
    }
}