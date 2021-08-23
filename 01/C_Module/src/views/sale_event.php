<section id="sale-event" class="mb-5">
    <div class="search">
        <form>
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="빵 이름을 입력하세요." value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>">
                <button class="btn btn-outline-brown">검색</button>
            </div>
        </form>
    </div>

    <div class="bread-list">
        <h3 class="text-center mt-5 mb-4">할인 메뉴</h3>
        <div class="breads mb-5">
            <?php foreach ($list as $b) : ?>
                <div class="card">
                    <img src="/C 모듈/<?= $b->image ?>" class="card-img-top" alt="img" title="img">
                    <div class="card-body">
                        <h5 class="card-title"><?= $b->storeName ?></h5>
                        <p class="card-text mb-1">원가 : <?= number_format($b->price) ?>원</p>
                        <p class="card-text mb-1">할인율 : <?= $b->sale ?>%</p>
                        <?php $salePrice = $b->price - $b->price * ($b->sale / 100); ?>
                        <p class="card-text mb-1">할인가 : <?= number_format($salePrice) ?>원</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>