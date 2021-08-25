<section class="my-5 py-5 d-flex justify-content-evenly">
    <!-- 내 정보 영역 -->
    <div class="profile">
        <div class="row">
            <label for="location" class="mb-2">지역 선택</label>
            <select id="location" class="form-select">
                <?php foreach ($locationList as $location) : ?>
                    <option value="<?= $location->id ?>"><?= $location->borough ?> <?= $location->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row">
            <label for="transportation" class="mb-2">교통수단 선택</label>
            <select id="transportation" class="form-select">
                <option value="bike">자전거</option>
                <option value="motorcycle">오토바이</option>
            </select>
        </div>
    </div>
    
</section>