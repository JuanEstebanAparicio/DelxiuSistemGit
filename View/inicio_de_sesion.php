 <?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: panel.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restaurante</title>
  <link rel="stylesheet" href="../CSS/restaurante.css">
</head>
<body>
  <canvas id="bgCanvas"></canvas>
  <div class="overlay"></div>
  <div class="content">
    <h1>Bienvenido a Restaurante Gourmet</h1>
    <p>Deléitate con nuestros sabores auténticos y un ambiente cálido e inolvidable.</p>
    <button class="btn">Ver Menú</button>
  </div>

  <script>
    const canvas = document.getElementById('bgCanvas')
    const ctx = canvas.getContext('2d')

    canvas.width = window.innerWidth
    canvas.height = window.innerHeight

    const icons = ['🍽️', '🍷', '🥖', '🍕', '🍝', '🍲']
    const particles = []

    for (let i = 0; i < 30; i++) {
      particles.push({
        x: Math.random() * canvas.width,
        y: canvas.height + Math.random() * canvas.height,
        size: 32 + Math.random() * 10,
        speed: 0.3 + Math.random() * 0.7,
        icon: icons[Math.floor(Math.random() * icons.length)],
        opacity: 0.2 + Math.random() * 0.5,
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

    window.addEventListener('resize', () => {
      canvas.width = window.innerWidth
      canvas.height = window.innerHeight
    })
  </script>
</body>
</html>
