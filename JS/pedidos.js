document.querySelectorAll('.cancelar').forEach(btn => {
  btn.addEventListener('click', () => {
    if (confirm('¿Seguro que deseas cancelar este pedido?')) {
      alert('Solicitud de cancelación enviada al gestor de órdenes.');
      // Aquí podrías hacer un fetch a tu backend PHP
    }
  });
});
