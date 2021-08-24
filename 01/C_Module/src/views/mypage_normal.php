<?php

use Kty\App\DB;
use Kty\Library\TimeFormat;
?>
<section class="my-5 py-5 d-flex justify-content-evenly">
    <!-- 주문 조회 영역 -->
    <div class="order-check">
        <h3 class="text-center mb-4">주문조회</h3>
        <div class="order-cards">
            <?php $diIdx = -1; ?>
            <?php foreach ($diList as $di) : ?>
                <?php $diIdx += 1; ?>
                <?php if ($diIdx < count($diList) - 1) : ?>
                    <?php if ($diList[$diIdx]->id == $diList[$diIdx + 1]->id) continue; ?>
                <?php endif; ?>
                <div class="order-card">
                    <h5><?= $di->name ?></h5>
                    <p><?= TimeFormat::format($di->order_at) ?></p>
                    <p>종류 및 가격 수량</p>
                    <?php if ($di->state == "taking" || $di->state == "complete") : ?>
                        <p><?= DB::fetch("SELECT * FROM users WHERE id = ?", [$di->driver_id])->name ?></p>
                        <p class="mb-0"><?= TimeFormat::format($di->taking_at) ?></p>
                    <?php endif; ?>
                    <?php $stateArr = array("order" => "주문 대기", "accept" => "상품 준비 중", "reject" => "주문 거절", "taking" => "배달 중", "complete" => "배달 완료"); ?>
                    <?php foreach ($stateArr as $key => $value) : ?>
                        <?php if ($key == $di->state) : ?>
                            <div class="order-state"><?= $value ?></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="order-btns">
                        <button id="order-review-btn" class="btn btn-brown">리뷰</button>
                        <button id="order-gpa-btn" class="btn btn-brown">평점</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 예약 조회 영역 -->
    <div class="reservation-check">
        <h3 class="text-center mb-4">예약조회</h3>
        <div class="reservation-cards">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">빵집이름</h5>
                    <p>예약일시</p>
                    <p>예약신청일시</p>
                    <p>상태</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">빵집이름</h5>
                    <p>예약일시</p>
                    <p>예약신청일시</p>
                    <p>상태</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">빵집이름</h5>
                    <p>예약일시</p>
                    <p>예약신청일시</p>
                    <p>상태</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">빵집이름</h5>
                    <p>예약일시</p>
                    <p>예약신청일시</p>
                    <p>상태</p>
                </div>
            </div>
        </div>
    </div>
</section>