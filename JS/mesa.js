function cambiarCantidad(btn, delta) {
  const cantidadSpan = btn.parentElement.querySelector('.cantidad');
  let cantidad = parseInt(cantidadSpan.textContent);
  cantidad = Math.max(0, cantidad + delta);
  cantidadSpan.textContent = cantidad;
}

function agregarAlCarrito(btn) {
  const producto = btn.parentElement;
  const nombre = producto.querySelector('h3').textContent;
  const precio = producto.querySelector('p').textContent;
  const cantidad = parseInt(producto.querySelector('.cantidad').textContent);
  if (cantidad === 0) return;

  const lista = document.getElementById('lista-carrito');
  const item = document.createElement('li');
  item.textContent = `${nombre} x${cantidad} - ${precio}`;
  lista.appendChild(item);

  document.getElementById('contador-carrito').textContent =
    lista.querySelectorAll('li').length;

  producto.querySelector('.cantidad').textContent = 0;
}

function toggleCarrito() {
  document.getElementById('carrito').classList.toggle('activo');
}

function realizarOrden() {
  alert('Orden enviada a la cocina 🍽️');
  document.getElementById('lista-carrito').innerHTML = '';
  document.getElementById('contador-carrito').textContent = '0';
  document.getElementById('carrito').classList.remove('activo');
}
