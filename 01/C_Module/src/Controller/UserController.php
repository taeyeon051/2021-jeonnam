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

    public function myPageNormal()
    {
        $user = $_SESSION['user'];
        $deliveriesSql =
            "SELECT
            distinct d.id, s.name, d.driver_id, d.state, d.taking_at, d.order_at
            from stores s, deliveries d, delivery_items di
            where d.id = di.delivery_id and s.id = d.store_id and d.orderer_id = ?";

        $breadSql =
            "SELECT 
            di.id, di.delivery_id, di.bread_id, di.price, di.cnt, b.name
            FROM breads b, deliveries d
            JOIN delivery_items di
            ON d.id = di.delivery_id
            WHERE d.orderer_id = ? and b.id = di.bread_id";

        $deliveryList = DB::fetchAll($deliveriesSql, [$user->id]);
        $breadList = DB::fetchAll($breadSql, [$user->id]);
        $reservationList = DB::fetchAll("SELECT r.*, s.name FROM reservations r, stores s WHERE r.user_id = ? and r.store_id = s.id", [$user->id]);

        $this->render('mypage_normal', ['diList' => $deliveryList, 'breadList' => $breadList, 'reservationList' => $reservationList]);
    }

    public function myPageOwner()
    {
        $this->render('mypage_owner');
    }

    public function myPageRider()
    {
        $locationList = DB::fetchAll("SELECT * FROM locations");

        $this->render('mypage_rider', ['locationList' => $locationList]);
    }
}
