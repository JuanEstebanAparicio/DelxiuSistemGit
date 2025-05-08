document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal-editar');
    const closeBtn = document.querySelector('.modal .close');
  
    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
  
        // Extraer todos los datos del botón
        const data = this.dataset;
  
        // Asignar a los campos
        document.getElementById('editar-id').value = data.id;
        document.getElementById('editar-nombre').value = data.nombre;
        document.getElementById('editar-cantidad').value = data.cantidad;
        document.getElementById('editar-cantidad-minima').value = data.cantidadMinima;
        document.getElementById('editar-unidad').value = data.unidad;
        document.getElementById('editar-unidad-personalizada').value = data.unidadPersonalizada;
        document.getElementById('editar-costo').value = data.costo;
        document.getElementById('editar-categoria').value = data.categoria;
        document.getElementById('editar-categoria-personalizada').value = data.categoriaPersonalizada;
        document.getElementById('editar-fecha-ingreso').value = data.fechaIngreso;
        document.getElementById('editar-vencimiento').value = data.vencimiento;
        document.getElementById('editar-lote').value = data.lote;
        document.getElementById('editar-descripcion').value = data.descripcion;
        document.getElementById('editar-ubicacion').value = data.ubicacion;
        document.getElementById('editar-estado').value = data.estado;
        document.getElementById('editar-proveedor').value = data.proveedor;
  
        // Vista previa de imagen
        const preview = document.getElementById('editar-foto-preview');
        if (data.foto) {
          preview.src = `../ruta/del/servidor/${data.foto}`;
          preview.style.display = 'block';
        } else {
          preview.style.display = 'none';
        }
  
        // Mostrar modal
        modal.style.display = 'block';
      });
    });
  
    // Cerrar modal
    window.cerrarModal = function () {
      modal.style.display = 'none';
    };
  });
  