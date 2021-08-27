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
                    $("#select-card").fadeIn('slow').css('display', 'flex');
                }
            });
        });

        const stampFile = document.querySelector("#stamp-file");
        stampFile.addEventListener("click", async e => {
            e.preventDefault();

            const [fileHandle] = await window.showOpenFilePicker();
            const file = await fileHandle.getFile();
            document.querySelector("#stamp-file-name").innerHTML = file.name;
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                const image = new Image();
                image.src = reader.result;
                image.onload = async () => {
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");
                    canvas.width = image.width;
                    canvas.height = image.height;
                    await this.drawImg(ctx, image);
                    await this.addStamp(ctx);

                    $("#stamp-file-name").html("선택된 파일 없음");
                    this.closePopup();

                    setTimeout(() => {
                        canvas.toBlob(async blob => {
                            await this.writeFile(fileHandle, blob, "stamp");
                        });
                    }, 0);
                }
            }
        });

        const eventFile = document.querySelector("#event-file");
        eventFile.addEventListener("click", async e => {
            e.preventDefault();

            const [fileHandle] = await window.showOpenFilePicker();
            const file = await fileHandle.getFile();
            document.querySelector("#event-file-name").innerHTML = file.name;
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                const image = new Image();
                image.src = reader.result;
                image.onload = async () => {
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");
                    canvas.width = image.width;
                    canvas.height = image.height;
                    await this.drawImg(ctx, image);

                    const check = await this.participateEventCheck(ctx, canvas.width, canvas.height);
                    if (check) {
                        const roulette = $(".right-roulette img");
                        $(roulette).css({ 'transition': 'none', 'transform': 'none' });
                        let random = this.randomNumber();
                        
                        let deg = 0;
                        const animation = setInterval(() => {
                            deg += (360 * 3 + (10 - random) * 36) / 30;
                            if (deg >= (360 * 3 + (10 - random) * 36)) {
                                deg = (360 * 3 + (10 - random) * 36);
                                clearInterval(animation);
                                $(roulette).css({'transition': 'none', 'transform': `rotateZ(${deg}deg)` });
                                $("#event-file-name").html("선택된 파일 없음");
                                setTimeout(() => {
                                    canvas.toBlob(async blob => {
                                        await this.writeFile(fileHandle, blob, "event", random);
                                    });
                                }, 1000);
                            }
                            $(roulette).css({'transition': '1s ease-out', 'transform': `rotateZ(${deg}deg)` });
                        }, 1000 / 30);
                    } else {
                        alert("참여 횟수가 모두 차감되어 이벤트에 참여할 수 없습니다.");
                        $("#event-file-name").html("선택된 파일 없음");
                    }
                }
            }
        });
    }

    randomNumber() {
        return Math.floor(Math.random() * 10);
    }

    participateEventCheck(ctx, w, h) {
        let check = false;
        const image = ctx.getImageData(0, 0, w, h);
        const rcanvas = document.createElement("canvas");
        const rctx = rcanvas.getContext("2d");
        const gcanvas = document.createElement("canvas");
        const gctx = gcanvas.getContext('2d');
        rcanvas.width = gcanvas.width = w;
        rcanvas.height = gcanvas.height = h;
        rctx.putImageData(image, 0, 0);
        gctx.putImageData(image, 0, 0);
        this.drawCircle(rctx, 163, 271, 'red');
        this.drawCircle(gctx, 163, 271, '#00d701');

        let i = 0;
        while (i < 8) {
            let x, y;
            x = 163 + i * 15;
            y = 271;
            const red = rctx.getImageData(163, 271, 2, 2);
            const green = gctx.getImageData(163, 271, 2, 2);
            const getImage = ctx.getImageData(x, y, 2, 2);

            if (JSON.stringify(red.data) == JSON.stringify(getImage.data)) {
                i++;
            } else if (JSON.stringify(green.data) == JSON.stringify(getImage.data)) {
                check = true;
                this.drawCircle(ctx, x, y, 'red');
                break;
            } else {
                check = false;
                break;
            }
        }

        return check;
    }

    addStamp(ctx) {
        const stampImg = new Image();
        stampImg.src = "/B 모듈/stamp/스탬프.png";
        stampImg.onload = () => {
            const scanvas = document.createElement("canvas");
            const sctx = scanvas.getContext('2d');
            const { width: w, height: h } = stampImg;
            scanvas.width = w;
            scanvas.height = h;
            this.drawImg(sctx, stampImg);

            let i = 0;
            while (i < 8) {
                let x, y;
                x = i < 4 ? 103 * i + 20 : 103 * (i - 4) + 20;
                y = i < 4 ? 78 : 173;
                const stamp = sctx.getImageData(0, 0, w, h);
                const getStamp = ctx.getImageData(x, y, w, h);

                if (JSON.stringify(stamp.data) == JSON.stringify(getStamp.data)) {
                    i++;
                } else {
                    this.drawCircle(ctx, 163 + i * 15, 271, '#00d701');
                    ctx.putImageData(stamp, x, y);
                    break;
                }
            }
        }
    }

    async writeFile(fileHandle, blob, se, random) {
        try {
            const writable = await fileHandle.createWritable();
            await writable.write(blob);
            await writable.close();
            if (se == "stamp") {
                alert("스탬프를 찍었습니다.");
            } else if (se == "event") {
                alert(`축하합니다. ${this.couponList[random]}에 당첨되었습니다.`);
            }
        } catch (e) {
            log(e);
        }
    }

    drawImg(ctx, img) {
        ctx.drawImage(img, 0, 0);
    }

    drawCircle(ctx, x, y, color) {
        ctx.fillStyle = color;
        ctx.arc(x, y, 3, 0, Math.PI * 2);
        ctx.fill();
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