
    // JS/inicio.js

// Iniciar fondo con Vanta.js
VANTA.WAVES({
  el: "#vanta-bg",
  THREE: window.THREE,
  color: 0x4b2e2e,
  shininess: 50,
  waveHeight: 20,
  waveSpeed: 1,
  zoom: 1.2,
})

// Animación de emojis
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
// Animación de maquina de escribir
new Typed("#typed-text", {
  strings: [
    "Gestiona tu restaurante con estilo",
    "Agiliza tus pedidos ",
    "Dale vida a tus sueños"
  ],
  typeSpeed: 50,
  backSpeed: 25,
  backDelay: 2000,
  loop: true
})
// Animación de nombre
function animateTitleLetters(selector = '.animated-title') {
  const title = document.querySelector(selector)
  if (!title) return

  const text = title.textContent

title.textContent = ''
  text.split('').forEach((char, i) => {
    const span = document.createElement('span')
    span.textContent = char
    span.style.opacity = 0
    span.style.display = 'inline-block'
    span.style.animation = `letterInEffect 0.4s ease forwards`
    span.style.animationDelay = `${i * 0.05}s`
    title.appendChild(span)
  })
}

animateTitleLetters()
