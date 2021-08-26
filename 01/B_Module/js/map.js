window.onload = () => {
    const app = new App();
}

const log = console.log;

class App {
    constructor() {
        this.locationList = [];
        this.mapViewBtn = document.querySelectorAll(".bakery-title>button");
        this.mapPopup = document.querySelector("#map-popup");

        this.canvas = document.querySelector("#map-popup canvas");
        this.ctx = this.canvas.getContext('2d');
        this.phaseImages = [
            [
                {
                    img: this.loadImage('/B 모듈/map/1/1.jpg'),
                    x: 0,
                    y: 0
                }
            ],
            [
                {
                    img: this.loadImage('/B 모듈/map/2/2-1.jpg'),
                    x: 0,
                    y: 0
                },
                {
                    img: this.loadImage('/B 모듈/map/2/2-2.jpg'),
                    x: 800,
                    y: 0
                },
                {
                    img: this.loadImage('/B 모듈/map/2/2-3.jpg'),
                    x: 0,
                    y: 800
                },
                {
                    img: this.loadImage('/B 모듈/map/2/2-4.jpg'),
                    x: 800,
                    y: 800
                }
            ],
            [
                {
                    img: this.loadImage('/B 모듈/map/3/3-1.jpg'),
                    x: 0,
                    y: 0
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-2.jpg'),
                    x: 800,
                    y: 0
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-3.jpg'),
                    x: 1600,
                    y: 0
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-4.jpg'),
                    x: 2400,
                    y: 0
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-5.jpg'),
                    x: 0,
                    y: 800
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-6.jpg'),
                    x: 800,
                    y: 800
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-7.jpg'),
                    x: 1600,
                    y: 800
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-8.jpg'),
                    x: 2400,
                    y: 800
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-9.jpg'),
                    x: 0,
                    y: 1600
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-10.jpg'),
                    x: 800,
                    y: 1600
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-11.jpg'),
                    x: 1600,
                    y: 1600
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-12.jpg'),
                    x: 2400,
                    y: 1600
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-13.jpg'),
                    x: 0,
                    y: 2400
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-14.jpg'),
                    x: 800,
                    y: 2400
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-15.jpg'),
                    x: 1600,
                    y: 2400
                },
                {
                    img: this.loadImage('/B 모듈/map/3/3-16.jpg'),
                    x: 2400,
                    y: 2400
                }
            ]
        ];
        this.currentPhase = 0;
        this.phaseSize = [800, 1600, 3200];
        this.isDragging = false;
        this.cameraPos = { x: 0, y: 0 };

        this.init();
    }

    init() {
        fetch('/B 모듈/store_location.json')
            .then(res => res.json())
            .then(data => {
                this.locationList = data;
                log(this.locationList);
                this.addEvent();
                this.render();
            });
    }

    addEvent() {
        const { mapViewBtn, mapPopup, canvas: c } = this;

        $(mapViewBtn).on("click", e => {
            $(mapPopup).fadeIn('slow').css('display', 'flex');
        });

        const closeBtn = document.querySelectorAll(".close-btn");
        $(closeBtn).on("click", () => this.closePopup());

        c.addEventListener("mousedown", e => this.mouseDown());
        c.addEventListener("mousemove", e => this.mouseMove(e));
        c.addEventListener("mouseup", e => this.mouseUp());
        c.addEventListener("mouseleave", e => this.mouseUp());
        c.addEventListener("mousewheel", e => this.mouseWheel(e));
    }

    render() {
        const { ctx, phaseImages, currentPhase, cameraPos } = this;

        ctx.clearRect(0, 0, 800, 800);

        phaseImages[currentPhase].forEach((obj, idx) => {
            const img = obj.img;
            const x = obj.x;
            const y = obj.y;

            const dx = x + cameraPos.x;
            const dy = y + cameraPos.y;
            // const dw = 800 - dx;
            // const dh = 800 - dy;

            // context.setTransform(1, 0, 0, 1, 0, 0);
            
            ctx.drawImage(img, dx, dy, 800, 800);
        });
    }

    loadImage(src) {
        const img = new Image();
        img.src = src;
        return img;
    }

    mouseDown() {
        this.isDragging = true;
    }

    mouseMove(e) {
        if (!this.isDragging) return;

        this.cameraPos.x += e.movementX;
        this.cameraPos.y += e.movementY;

        const { cameraPos, phaseSize, currentPhase } = this;
        if (cameraPos.x > 0) this.cameraPos.x = 0;
        if (cameraPos.y > 0) this.cameraPos.y = 0;
        if ((phaseSize[currentPhase] - 800) < Math.abs(cameraPos.x)) this.cameraPos.x = (phaseSize[currentPhase] * -1) + 800;
        if ((phaseSize[currentPhase] - 800) < Math.abs(cameraPos.y)) this.cameraPos.y = (phaseSize[currentPhase] * -1) + 800;

        this.render();
    }

    mouseUp() {
        this.isDragging = false;
    }

    mouseWheel(e) {
        const { currentPhase } = this;
        if (e.wheelDelta > 0 && currentPhase < 2) this.phaseUp(e);
        else if (e.wheelDelta < 0 && currentPhase > 0) this.phaseDown(e);
    }

    phaseUp(e) {
        const { ctx } = this;
        const { offsetX: x, offsetY: y } = e;
        // ctx.translate(x, y);
        // ctx.scale(2, 2);
        // ctx.translate(-x, -y);
        const prevPhase = this.currentPhase;
        this.currentPhase++;
        this.doZoom(prevPhase, x, y);
    }

    phaseDown(e) {
        const { ctx } = this;
        const { offsetX: x, offsetY: y } = e;
        // ctx.translate(x, y);
        // ctx.scale(1, 1);
        // ctx.translate(-x, -y);
        const prevPhase = this.currentPhase;
        this.currentPhase--;
        this.doZoom(prevPhase, x, y);
    }

    doZoom(prevPhase, x, y) {
        const { phaseSize, currentPhase } = this;

        const norX = (x + Math.abs(this.cameraPos.x)) / phaseSize[prevPhase];
        const norY = (y + Math.abs(this.cameraPos.y)) / phaseSize[prevPhase];

        const newX = norX * phaseSize[currentPhase] * -1 + 400;
        const newY = norY * phaseSize[currentPhase] * -1 + 400;
        const limit = phaseSize[currentPhase] - 800;

        this.cameraPos.x = newX;
        this.cameraPos.y = newY;

        if (Math.abs(this.cameraPos.x) > limit) {
            this.cameraPos.x = limit * -1;
        }

        if (this.cameraPos.x > 0) {
            this.cameraPos.x = 0;
        }

        if (Math.abs(this.cameraPos.y) > limit) {
            this.cameraPos.y = limit * -1;
        }

        if (this.cameraPos.y > 0) {
            this.cameraPos.y = 0;
        }

        this.render();
    }

    closePopup() {
        const popup = document.querySelectorAll(".popup");
        $(".popup input").val("");
        $(popup).fadeOut('slow');
    }
}