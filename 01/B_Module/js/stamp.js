const log = console.log;

window.onload = () => {
    const stamp = new Stamp();
}

class Stamp {
    constructor() {
        this.codeList = [];
        this.couponList = [];
        this.canvas = document.createElement("canvas");
        this.ctx = this.canvas.getContext('2d');
        this.stampImage = new Image();
        this.stampImage.src = "/B 모듈/stamp/스탬프.png";

        this.init();
        this.addEvent();
    }

    init() {
        fetch('/B 모듈/code.json')
            .then(res => res.json())
            .then(async data => {
                this.codeList = await data;
            });

        fetch('/B 모듈/product.json')
            .then(res => res.json())
            .then(async data => {
                this.couponList = await data;
            });
    }

    addEvent() {
        const issueStampBtn = document.querySelector("#issue-stamp-btn");
        const closeBtn = document.querySelectorAll(".close-btn");

        $(issueStampBtn).on("click", () => {
            $("#stamp-popup").fadeIn('slow');
            $("#stamp-popup").css('display', 'flex');
        });

        $(closeBtn).on("click", () => this.closePopup());

        const stampName = document.querySelector("#stamp-name");
        const stampDown = document.querySelector("#stamp-download");
        $(stampDown).on("click", () => {
            const name = stampName.value;
            if (name.trim() === "") return;

            const { canvas: c, ctx } = this;
            const img = new Image();
            img.src = "/B 모듈/stamp/스탬프카드.png";
            img.onload = () => {
                c.width = img.width;
                c.height = img.height;
                ctx.clearRect(0, 0, c.width, c.height);

                this.drawImg(ctx, img);
                this.drawName(ctx, name);
                this.imgDownload(c, "stamp_card");
                this.closePopup();
            }
        });

        const stampCode = document.querySelector("#code-input");
        const codeInputBtn = document.querySelector("#code-input-btn");
        $(codeInputBtn).on("click", () => {
            const code = stampCode.value;
            const { codeList } = this;
            codeList.forEach(c => {
                if (c == code) {
                    $("#select-card").fadeIn('slow');
                    $("#select-card").css('display', 'flex');
                }
            });
        });

        const stampFile = document.querySelector("#stamp-file");
        stampFile.addEventListener("change", e => {
            const file = e.target.files.length > 0 ? e.target.files[0] : null;
            log(e.target.value);
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext('2d');
            const copyCanvas = document.createElement("canvas");
            const copyCtx = copyCanvas.getContext('2d');
            const { width: w, height: h } = this.stampImage;

            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                const image = new Image();
                image.src = reader.result;
                image.onload = async () => {
                    canvas.width = copyCanvas.width = image.width;
                    canvas.height = copyCanvas.height = image.height;

                    await ctx.drawImage(image, 0, 0);
                    await copyCtx.drawImage(image, 0, 0);

                    for (let i = 0; i < 6; i++) {
                        let x, y;
                        x = i < 4 ? 103 * i + 20 : 103 * (i - 4) + 20;
                        y = i < 4 ? 78 : 173;

                        let stImg = ctx.getImageData(x, y, w, h);
                        if (i == 0) {
                            copyCtx.drawImage(this.stampImage, x, y);
                        }
                        let cx, cy;
                        cx = (i + 1) < 4 ? 103 * (i + 1) + 20 : 103 * ((i + 1) - 4) + 20;
                        cy = (i + 1) < 4 ? 78 : 173;
                        copyCtx.putImageData(stImg, cx, cy);
                    }

                    this.imgDownload(copyCanvas, file.name);
                    $("#code-input").val("");
                    this.closePopup();
                }
            }
        });
    }

    drawImg(ctx, img) {
        ctx.drawImage(img, 0, 0);
    }

    imgDownload(c, name) {
        const href = c.toDataURL();
        const a = document.createElement("a");
        a.href = href;
        a.download = name;
        a.click();
    }

    drawName(ctx, name) {
        ctx.fillStyle = "#fff";
        ctx.fillText(name, 365, 22);
    }

    closePopup() {
        const popup = document.querySelectorAll(".popup");
        $(".popup input").val("");
        $(popup).fadeOut('slow');
    }
}