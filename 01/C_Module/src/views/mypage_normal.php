<?php

use Kty\App\DB;
use Kty\Library\Format;
?>
<section class="my-5 py-5 d-flex justify-content-evenly" style="min-height: 400px;">
    <!-- 주문 조회 영역 -->
    <div class="order-check">
        <h3 class="text-center mb-4">주문조회</h3>
        <div class="order-cards">
            <?php foreach ($diList as $di) : ?>
                <div class="order-card">
                    <h5><?= $di->name ?></h5>
                    <p><?= Format::timeFormat($di->order_at) ?></p>
                    <ol>
                        <?php foreach ($breadList as $b) : ?>
                            <?php if ($b->delivery_id == $di->id) : ?>
                                <li><?= $b->name ?> <span><?= number_format($b->price) ?>원 (<?= $b->cnt ?>개)</span></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                    <?php if ($di->state == "taking" || $di->state == "complete") : ?>
                        <p><?= DB::fetch("SELECT * FROM users WHERE id = ?", [$di->driver_id])->name ?></p>
                        <p class="mb-0"><?= Format::timeFormat($di->taking_at) ?></p>
                    <?php endif; ?>
                    <div class="state"><?= Format::dstateFormat($di->state) ?></div>
                    <div class="order-btns">
                        <?php if ($di->state == "complete") : ?>
                            <button id="order-review-btn" class="btn btn-brown">리뷰</button>
                            <button id="order-gpa-btn" class="btn btn-brown">평점</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 예약 조회 영역 -->
    <div class="reservation-check">
        <h3 class="text-center mb-4">예약조회</h3>
        <div class="reservation-cards">
            <?php foreach ($reservationList as $r) : ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $r->name ?></h5>
                        <p class="mt-2">예약 일시<br><?= Format::timeFormat($r->reservation_at) ?></p>
                        <p class="mb-0">예약 신청 일시<br><?= Format::timeFormat($r->request_at) ?></p>
                        <div class="state"><?= Format::rstateFormat($r->state) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>