{{-- xmas --}}

@if (config('app.season') === 'xmas')
    <div id="seasons-container"></div>
    <style>
        body::before {
        content: "" !important;
        position: fixed !important;
        inset: 0 !important;
        background: url('/assets/christmas.jpg') center center / cover no-repeat !important;
        /* filter: blur(8px) !important; */
        transform: scale(1.1) !important; /* push edges outward */
        background-color: rgba(0, 0, 0, 0.5) !important; /* dark overlay */
        background-blend-mode: multiply !important;
        z-index: -1 !important;
    }
    </style>
    <script>
        (function () {
            // Create styles
            const style = document.createElement("style");
            style.innerHTML = `
                #seasons-container {
                    position: fixed;
                    inset: 0;
                    pointer-events: none;
                    z-index: 9999;
                    overflow: hidden;
                }

                .snowflake {
                    position: absolute;
                    top: -10px;
                    color: white;
                    font-size: 1em;
                    opacity: 0.8;
                    animation: fall linear infinite;
                    background: transparent !important;
                    border: none !important;
                }

                @keyframes fall {
                    0% {
                        transform: translateY(0) rotate(0deg);
                    }
                    100% {
                        transform: translateY(110vh) rotate(360deg);
                    }
                }

                .seasons-text {
                    position: fixed;
                    top: 20px;
                    width: 100%;
                    text-align: center;
                    font-family: 'Segoe UI', sans-serif;
                    font-size: 2.5rem;
                    font-weight: bold;
                    color: #ff4444;
                    text-shadow: 0 0 10px #fff, 0 0 20px #ff0000;
                    z-index: 10000;
                    animation: glow 2s infinite alternate;
                }

                @keyframes glow {
                    from {
                        text-shadow: 0 0 5px #fff, 0 0 10px #ff0000;
                    }
                    to {
                        text-shadow: 0 0 20px #fff, 0 0 40px #ff0000;
                    }
                }
            `;
            document.head.appendChild(style);

            // Add greeting text
            const text = document.createElement("div");
            text.className = "seasons-text";
            text.innerHTML = "";
            document.body.appendChild(text);

            // Add container
            const container = document.getElementById("seasons-container");

            // Create snowflakes
            function createSnowflake() {
                const snowflake = document.createElement("div");
                snowflake.className = "snowflake";
                snowflake.innerHTML = "❆";

                snowflake.style.left = Math.random() * 100 + "vw";
                snowflake.style.fontSize = Math.random() * 10 + 10 + "px";
                snowflake.style.animationDuration = Math.random() * 3 + 5 + "s";

                container.appendChild(snowflake);

                setTimeout(() => {
                    snowflake.remove();
                }, 8000);
            }

            setInterval(createSnowflake, 200);
        })();
    </script>
@endif

{{-- valentines --}}

@if (config('app.season') === 'valentines')
    <div id="seasons-container"></div>

    <style>
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: url('/assets/valentine.jpg') center center / cover no-repeat;
        background-color: rgba(255, 0, 100, 0.45);
        background-blend-mode: multiply;
        transform: scale(1.1);
        z-index: -1;
    }

    /* Floating container */
    #seasons-container {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 9999;
        overflow: hidden;
    }

    /* Heart style */
    .heart {
        position: absolute;
        top: -10vh;
        font-size: 10px;
        /* color: #ff4d6d; */
        opacity: 0.85;
        animation: float linear infinite;
    }

    /* Floating animation */
    @keyframes float {
        0% {
            transform: translateY(0) scale(1) rotate(0deg);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        100% {
            transform: translateY(120vh) scale(1.4) rotate(360deg);
            opacity: 0;
        }
    }

    /* Valentine text */
    .seasons-text {
        position: fixed;
        top: 20px;
        width: 100%;
        text-align: center;
        font-family: 'Segoe UI', sans-serif;
        font-size: 2.6rem;
        font-weight: bold;
        color: #ffb3c6;
        text-shadow: 0 0 10px #ff4d6d, 0 0 30px #ff1e56;
        z-index: 10000;
        animation: heartbeat 1.5s infinite;
    }

    /* Heartbeat animation */
    @keyframes heartbeat {
        0% { transform: scale(1); }
        25% { transform: scale(1.05); }
        50% { transform: scale(1); }
        75% { transform: scale(1.08); }
        100% { transform: scale(1); }
    }
    </style>

    <script>
    (function () {

        // Valentine text
        const text = document.createElement("div");
        text.className = "seasons-text";
        text.innerHTML = "";
        document.body.appendChild(text);

        const container = document.getElementById("seasons-container");

        function createHeart() {
            const heart = document.createElement("div");
            heart.className = "heart";

            // Different heart styles
            const hearts = ["❤️", "💖", "💕", "💘", "💗"];
            heart.innerHTML = hearts[Math.floor(Math.random() * hearts.length)];

            heart.style.left = Math.random() * 100 + "vw";
            heart.style.fontSize = Math.random() * 15 + 15 + "px";
            heart.style.animationDuration = Math.random() * 4 + 6 + "s";

            container.appendChild(heart);

            setTimeout(() => heart.remove(), 10000);
        }

        setInterval(createHeart, 250);
    })();
    </script>

@endif


@if (config('app.season') === 'nature')
    <div id="seasons-container"></div>

    <style>
    body::before {
        content: "";
        position: fixed;
        inset: 0;

        /* Nature background image */
        background: url('/assets/nature.jpg') center center / cover no-repeat;

        /* Natural green overlay */
        background-color: rgba(20, 60, 40, 0.35);
        background-blend-mode: multiply;

        /* Subtle depth */
        transform: scale(1.08);
        filter: blur(0.5px);

        z-index: -1;
    }

    /* Container */
    #seasons-container {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 9999;
        overflow: hidden;
    }

    /* Orb / Firefly */
    .nature-orb {
        position: absolute;
        width: 6px;
        height: 6px;
        background: radial-gradient(circle, #b6ffda 0%, #3cff9e 40%, transparent 70%);
        border-radius: 50%;
        opacity: 0.8;
        filter: blur(0.5px);
        animation: drift linear infinite;
    }

    /* Movement */
    @keyframes drift {
        0% {
            transform: translate(0, 0);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        50% {
            opacity: 0.9;
        }
        100% {
            transform: translate(var(--x), var(--y));
            opacity: 0;
        }
    }
    </style>

    <script>
    (function () {

        const container = document.getElementById("seasons-container");

        function createOrb() {
            const orb = document.createElement("div");
            orb.className = "nature-orb";

            // Random start position
            const startX = Math.random() * window.innerWidth;
            const startY = Math.random() * window.innerHeight;

            // Random drift direction
            const moveX = (Math.random() - 0.5) * 300;
            const moveY = (Math.random() - 0.5) * 300;

            orb.style.left = startX + "px";
            orb.style.top = startY + "px";

            orb.style.setProperty("--x", moveX + "px");
            orb.style.setProperty("--y", moveY + "px");

            orb.style.animationDuration = Math.random() * 6 + 6 + "s";
            orb.style.opacity = Math.random() * 0.6 + 0.4;

            // Slight size variation
            const size = Math.random() * 4 + 4;
            orb.style.width = size + "px";
            orb.style.height = size + "px";

            container.appendChild(orb);

            setTimeout(() => orb.remove(), 12000);
        }

        setInterval(createOrb, 350);

    })();
    </script>

@endif

@if (config('app.season') === 'rain')
    <div id="seasons-container"></div>
    <style>
        body::before {
            content: "";
            position: fixed;
            inset: 0;

            /* Nature / rainy background */
            background: url('/assets/rain.jpg') center center / cover no-repeat;

            /* Cool dark overlay */
            background-color: rgba(20, 30, 40, 0.45);
            background-blend-mode: multiply;

            /* Wet glass effect */
            filter: blur(1px);
            transform: scale(1.08);

            z-index: -1;
        }
        /* Rain container */
        #seasons-container {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 9999;
            overflow: hidden;
        }

        /* Raindrop */
        .raindrop {
            position: absolute;
            width: 2px;
            height: 14px;
            background: linear-gradient(
                to bottom,
                rgba(255,255,255,0),
                rgba(200,220,255,0.8)
            );
            opacity: 0.7;
            animation: rain linear infinite;
        }

        /* Falling animation */
        @keyframes rain {
            from {
        transform: translate(-20px, -20vh);
    }
    to {
        transform: translate(40px, 120vh);
    }
        }
            </style>

    <script>
        (function () {

            const container = document.getElementById("seasons-container");

            function createRain() {
                const drop = document.createElement("div");
                drop.className = "raindrop";

                drop.style.left = Math.random() * 100 + "vw";
                drop.style.animationDuration = Math.random() * 0.5 + 0.6 + "s";
                drop.style.opacity = Math.random() * 0.5 + 0.3;

                container.appendChild(drop);

                setTimeout(() => drop.remove(), 1500);
            }

            // Density control
            setInterval(createRain, 20);

        })();
    </script>

@endif