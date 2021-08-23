<?php

namespace Kty\Controller;

use Kty\App\DB;

class MainController extends MasterController
{
    public function index()
    {
        $this->render('main');
    }

    public function daejeonBakery()
    {
        $storeSql =
            "SELECT 
            s.id, s.name, s.connect, 
            (select avg(g.score) from grades g where s.id = g.store_id) gpa,
            (select count(*) from reviews where s.id = reviews.store_id) review, s.image, concat(l.borough, ' ', l.name) `location`,
            SUM(if(d.bread_id = b.id, d.cnt, 0)) sell, rank() OVER(ORDER BY sell desc) 'ranking'
            FROM delivery_items d, breads b
            JOIN stores s
            ON b.store_id = s.id
            JOIN users u
            ON u.id = s.user_id
            JOIN locations l
            ON s.user_id = u.id and u.location_id = l.id
            GROUP BY b.store_id
            ORDER BY sell DESC;";

        $storeList = DB::fetchAll($storeSql);

        $this->render('daejeon_bakery', ['list' => $storeList]);
    }

    public function menuCheck()
    {
        $keyword = $_POST['keyword'];
        $sql = "SELECT * FROM breads WHERE BINARY(name) LIKE ?";
        $result = DB::fetchAll($sql, ['%' . $keyword . '%']);
        if ($result) echo json_encode($result, JSON_UNESCAPED_UNICODE);
        else echo "실패";
    }

    public function saleEvent()
    {
        $keyword = '%%';

        if (isset($_GET['keyword'])) $keyword = '%' . $_GET['keyword'] . '%';

        $sql = 
            "SELECT b.*, s.name storeName
            FROM breads b
            JOIN stores s
            ON b.store_id = s.id
            WHERE b.sale > 0 and BINARY(b.name) LIKE ?
            ORDER BY b.sale DESC";

        $result = DB::fetchAll($sql, [$keyword]);

        $this->render('sale_event', ['list' => $result]);
    }
}
