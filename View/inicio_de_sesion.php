 <!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restaurante</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden;
      font-family: 'Segoe UI', sans-serif;
      background: #000;
    }

    #vanta-bg {
      position: absolute;
      width: 100%;
      height: 100%;
      z-index: 1;
    }

    #emoji-canvas {
      position: absolute;
      width: 100%;
      height: 100%;
      z-index: 2;
      pointer-events: none;
    }

    .overlay {
      position: absolute;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: 3;
    }

    .content {
      position: relative;
      z-index: 4;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff8e1;
      text-align: center;
      padding: 20px;
    }

    h1 {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    p {
      font-size: 1.2rem;
      max-width: 600px;
      margin-bottom: 2rem;
    }

    .btn {
      background: #fff8e1;
      color: #4b2e13;
      padding: 12px 24px;
      font-size: 1rem;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: #f2e0c2;
    }
  </style>
</head>
<body>
  <div id="vanta-bg"></div>
  <canvas id="emoji-canvas"></canvas>
  <div class="overlay"></div>
  <div class="content">
    <h1>Bienvenido a Restaurante Gourmet</h1>
    <p>Deléitate con nuestros sabores auténticos y un ambiente cálido e inolvidable.</p>
    <button class="btn">Ver Menú</button>
  </div>

  <!-- VANTA + THREE -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>

  <script>
    VANTA.WAVES({
      el: "#vanta-bg",
      THREE: window.THREE,
      color: 0x4b2e2e,
      shininess: 50,
      waveHeight: 20,
      waveSpeed: 1,
      zoom: 1.2,
    });
  </script>

  <!-- Emoji Animation -->
  <script>
    const canvas = document.getElementById('emoji-canvas')
    const ctx = canvas.getContext('2d')

    function resizeCanvas() {
      canvas.width = window.innerWidth
      canvas.height = window.innerHeight
    }

    resizeCanvas()
    window.addEventListener('resize', resizeCanvas)

    const icons = ['🍽️', '🍷', '🍕', '🍝', '🥖', '🍲']
    const particles = []

    for (let i = 0; i < 40; i++) {
      particles.push({
        x: Math.random() * canvas.width,
        y: canvas.height + Math.random() * canvas.height,
        size: 32 + Math.random() * 12,
        speed: 0.3 + Math.random() * 0.8,
        icon: icons[Math.floor(Math.random() * icons.length)],
        opacity: 0.2 + Math.random() * 0.6,
      })
    }

    function draw() {
      ctx.clearRect(0, 0, canvas.width, canvas.height)
      particles.forEach(p => {
        ctx.font = `${p.size}px serif`
        ctx.globalAlpha = p.opacity
        ctx.fillText(p.icon, p.x, p.y)
        p.y -= p.speed
        if (p.y < -50) {
          p.y = canvas.height + Math.random() * 100
          p.x = Math.random() * canvas.width
        }
      })
      requestAnimationFrame(draw)
    }

    draw()
  </script>
</body>
</html>
