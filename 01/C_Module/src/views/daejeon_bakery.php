<section id="sub-main-section" class="mb-5">
    <div class="search">
        <div class="input-group">
            <select class="form-select" id="type" id="search-bread">
                <option value="name">빵집이름</option>
                <option value="menu">메뉴</option>
                <option value="local">지역</option>
            </select>
            <input type="text" id="keyword" class="form-control">
            <button id="search-btn" class="btn btn-outline-brown">검색</button>
        </div>
    </div>
    <div class="store-list">
        <div>
            <h3 class="text-center mt-5 mb-4">베스트 빵집</h3>
            <div class="bread-store-list mb-5">
                <?php for ($i = 0; $i < count($list); $i++) : ?>
                    <?php if ($list[$i]->ranking <= 5 && $i < 5) : ?>
                        <div class="card" data-idx="<?= $list[$i]->id ?>">
                            <img src="/C 모듈/<?= $list[$i]->image ?>" class="card-img-top" alt="img" title="img">
                            <div class="card-body">
                                <h5 class="card-title"><?= $list[$i]->name ?></h5>
                                <p class="card-text mb-1 local"><?= $list[$i]->location ?></p>
                                <p class="card-text mb-1"><i class="fa fa-phone me-2"></i><?= $list[$i]->connect ?></p>
                                <p class="card-text mb-1">평점 : <?= $list[$i]->gpa == null ? 0 : $list[$i]->gpa ?>점</p>
                                <p class="card-text">리뷰 : <?= $list[$i]->review ?>개</p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
        <div>
            <h3 class="text-center mt-5 mb-4">빵집 리스트</h3>
            <div class="bread-store-list">
                <?php for ($i = 0; $i < count($list); $i++) : ?>
                    <?php if ($list[$i]->ranking > 4 && $i > 4) : ?>
                        <div class="card" data-idx="<?= $list[$i]->id ?>">
                            <img src="/C 모듈/<?= $list[$i]->image ?>" class="card-img-top" alt="img" title="img">
                            <div class="card-body">
                                <h5 class="card-title"><?= $list[$i]->name ?></h5>
                                <p class="card-text mb-1 local"><?= $list[$i]->location ?></p>
                                <p class="card-text mb-1"><i class="fa fa-phone me-2"></i><?= $list[$i]->connect ?></p>
                                <p class="card-text mb-1">평점 : <?= $list[$i]->gpa == null ? 0 : $list[$i]->gpa ?>점</p>
                                <p class="card-text">리뷰 : <?= $list[$i]->review ?>개</p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<script>
    window.onload = () => {
        const log = console.log;

        const list = JSON.parse('<?= json_encode($list, JSON_UNESCAPED_UNICODE) ?>');
        const searchBtn = document.querySelector("#search-btn");
        $(searchBtn).on("click", () => search());
        window.addEventListener("keydown", e => {
            if (e.keyCode == 13) search();
        });

        function search() {
            const type = document.querySelector("#type").value;
            const keyword = document.querySelector("#keyword").value;
            const cards = document.querySelectorAll(".card");

            if (keyword.trim() === "") {
                cards.forEach(card => {
                    card.classList.replace("d-none", "d-flex");
                });
            }

            if (type.trim() !== "" && keyword.trim() !== "") {
                if (type == "menu") {
                    $.ajax({
                        url: '/menu/check',
                        type: 'POST',
                        data: {
                            keyword
                        },
                        success: async e => {
                            if (e == "실패") {
                                cards.forEach(card => {
                                    card.classList.add("d-none");
                                    card.classList.replace("d-flex", "d-none");
                                });
                            } else {
                                let arr = await JSON.parse(e);
                                cards.forEach(card => {
                                    card.classList.add("d-none");
                                    card.classList.replace("d-flex", "d-none");
                                    if (arr.find(f => f.store_id == card.dataset.idx)) {
                                        card.classList.replace("d-none", "d-flex");   
                                    }
                                });
                            }
                        }
                    });
                } else {
                    cards.forEach(card => {
                        card.classList.add("d-none");
                        card.classList.replace("d-flex", "d-none");
                        if (type == "name") {
                            const name = card.querySelector(".card-title").innerText;
                            if (name.indexOf(keyword.trim()) != -1) card.classList.replace("d-none", "d-flex");
                        } else if (type == "local") {
                            log(card.querySelector(".local"));
                            const local = card.querySelector(".local").innerText;
                            if (local.indexOf(keyword.trim()) != -1) card.classList.replace("d-none", "d-flex");
                        }
                    });
                }
            }
        }
    }
</script>