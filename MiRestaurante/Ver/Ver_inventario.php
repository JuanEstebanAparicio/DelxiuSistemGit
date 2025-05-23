<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
  header("Location: ../Ver/Login.php");
  exit();
}

include '../modelo/conexion.php';
require '../modelo/filtros.php';
$id_usuario = $_SESSION['id_usuario'];

$resultado_filtrado = construirFiltroInventario($conexion, $id_usuario);
$resultado = $resultado_filtrado;

$contador = [
  'activo' => 0,
  'agotado' => 0,
  'vencido' => 0,
  'bajo-stock' => 0,
  'no-disponible' => 0
];
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inventario de Ingredientes</title>
  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (con Popper incluido) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../Ver/css/estilos.css">
    <script src="../controlador/js/funcionalidad.js" defer></script>
  <script src="../js/gestion_ingredientes.js" defer></script>

  <!-- Librerias datatable export -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

  <style>
    /* Fondo oscuro de fondo del modal */
/* Nuevo: Solo fondo visual, sin romper bootstrap */
.modal-custom-bg {
  background-color: rgba(224, 228, 238, 0.92);
  backdrop-filter: blur(3px);
  border-radius: 12px;
}

.scroll-text {
        max-height: 100px;
        max-width: 300px;
        overflow: auto;
        white-space: pre-wrap;
    }
.scroll-horizontal {
    overflow-x: auto;
    display: block;
    white-space: nowrap;
}
/* Contenedor del contenido del modal */
.modal-contenido {
  background-color: #fff;
  margin: 60px auto;
  padding: 30px 40px;
  border-radius: 12px;
  max-width: 600px;
  width: 95%;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
  position: relative;
  animation: slideDown 0.3s ease;
}

/* Botón cerrar */
.cerrar {
  color: #999;
  font-size: 28px;
  font-weight: bold;
  position: absolute;
  right: 20px;
  top: 15px;
  cursor: pointer;
  transition: 0.3s;
}

.cerrar:hover {
  color: #333;
}

/* Estilo para campos del formulario */
#form-editar input,
#form-editar select,
#form-editar textarea {
  width: 100%;
  padding: 10px 12px;
  margin: 10px 0 20px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 15px;
}

/* Botón de enviar */
#form-editar button[type="submit"] {
  background-color: #28a745;
  color: white;
  padding: 12px 20px;
  font-size: 16px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  width: 100%;
  transition: background-color 0.3s;
}

#form-editar button[type="submit"]:hover {
  background-color: #218838;
}

/* Imagen de vista previa */
#edit_preview {
  max-width: 100%;
  height: auto;
  display: block;
  margin-top: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

/* Animaciones */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideDown {
  from { transform: translateY(-20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

    .btn-action {
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      margin: 0 2px;
    }
    .btn-edit {
      background-color: #007bff;
      color: white;
    }
    .btn-delete {
      background-color: #dc3545;
      color: white;
    }
    .btn-edit:hover {
      background-color: #0056b3;
    }
    .btn-delete:hover {
      background-color: #a71d2a;
    }
    .filtros {
      margin-bottom: 20px;
    }
    .filtros select {
      margin-right: 10px;
    }
    .estado-leyenda .cuadro {
  display: inline-block;
  width: 20px;
  height: 20px;
  margin-right: 5px;
  vertical-align: middle;
  border: 1px solid #333;
}

.estado-leyenda .vencido {
  background-color: #d32f2f;
}
.estado-leyenda .agotado {
  background-color:rgba(158, 158, 158, 0.58);
}
.estado-leyenda .bajo-stock {
  background-color: #fff176;
}
.estado-leyenda .activo {

}
.estado-leyenda .no-disponible {
  background-color: #b0bec5;
}
</style>
</head>
<body>
<header class="top-bar">
  <a href=""></a>
  <a href="../Ver/Inicio.php" class="btn amarillo">Inicio</a>
    <a href="../Ver/gestor_menu.php" class="btn azul">Gestion de menu</a>
      <a href="../Ver/registro_ingrediente.php" class="btn verde">Inventario</a>
  <a href="#" class="btn red">Ver inventario</a>
  <a href="../Ver/gestor_ordenes.php" class="btn amarillo">Gestor de ordenes </a>
  <div class="config-container">
    <button class="engranaje" onclick="toggleMenu()">⚙️</button>
    <div class="config-menu" id="configMenu">
      <a href=""></a>
      <a href="../css/estilos.css" id="cambiarTema">🌓 Cambiar Tema</a>
      <a href="../Ver/ver_historial.php">⏳historial de inventario</a>
      <a href="../Ver/perfil_usuario.php">👤 Perfil</a>
      <a href="../controlador/php/logout.php">🚪 Cerrar Sesión</a>
    </div>
  </div>
</header>

<h2>📦 Inventario Actual</h2>

<form method="GET" class="mb-4 d-flex gap-3">
  <div>
    <label for="categoria">Categoría</label>
    <select name="categoria" id="categoria" class="form-select">
      <option value="">Todas</option>
      <option value="Verduras">Verduras</option>
      <option value="Frutas">Frutas</option>
      <option value="Lácteos">Lácteos</option>
      <option value="Carnes">Carnes</option>
      <option value="Cereales y granos">Cereales y granos</option>
      <option value="Panadería">Panadería</option>
      <option value="Enlatados">Enlatados</option>
      <option value="Especias">Especias</option>
      <option value="Snacks">Snacks</option>
      <option value="Bebidas">Bebidas</option>
      <option value="otro">Otra...</option>
    </select>
  </div>

  <div>
    <label for="unidad_medida">Unidad de Medida</label>
    <select name="unidad_medida" id="unidad_medida" class="form-select">
      <option value="">Todas</option>
      <option value="kg">Kilogramos</option>
      <option value="g">Gramos</option>
      <option value="l">Litros</option>
      <option value="ml">Mililitros</option>
      <option value="unidades">Unidades</option>
      <option value="otro">Otra...</option>
    </select>
  </div>
  
  <div class="align-self-end">
    <button type="submit" class="btn btn-primary">Filtrar</button>
  </div>

  <div class="mb-3">
  <label for="filtroEstado">Filtrar por estado</label>
  <select id="filtroEstado" class="form-select" onchange="filtrarPorEstado()">
    <option value="">Todos</option>
    <option value="activo">Activo</option>
    <option value="agotado">Agotado</option>
    <option value="vencido">Vencido</option>
    <option value="no-disponible">No disponible</option>
    <option value="bajo-stock">Bajo stock</option>
  </select>
</div>
</form>

<div class="estado-leyenda mb-3" style="display: flex; gap: 1rem; flex-wrap: wrap;">
  <div><span class="cuadro vencido"></span> Vencido</div>
  <div><span class="cuadro agotado"></span> Agotado</div>
  <div><span class="cuadro bajo-stock"></span> Bajo stock</div>
  <div><span class="cuadro activo"></span> Activo</div>
  <div><span class="cuadro no-disponible"></span> No disponible</div>
</div>
<div class="scroll-horizontal">


<table id="tablaInventario" class="display nowrap" style="width:100%">
  
<thead>
    <tr>
      <th>Nombre</th>
      <th>Cantidad</th>
      <th>Cantidad minima</th>
      <th>Unidad de medida</th>
      <th>Costo_unitario</th>
      <th>Valor total</th>
      <th>Categoría</th>
      <th> fecha de Vencimiento</th>
      <th>Lote</th>
      <th>Descripcion</th>
      <th>Ubicacion de almacen</th>
      <th>Estado</th>
      <th>Proveedor</th>
      <th>Última actualización</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
  
  <?php while ($row = $resultado->fetch_assoc()): 
 
      $fecha_actual = date('Y-m-d');
      $fecha_vencimiento = $row['fecha_vencimiento'];
      $cantidad = floatval($row['cantidad']);
      $cantidad_minima = floatval($row['cantidad_minima']);

      // Si el estado es 'no disponible', respetarlo sin cambios
      if (isset($row['id_Ingrediente'])) {
        $id = intval($row['id_Ingrediente']);

      if ($row['estado'] === 'no disponible') {
        $clase_fila = 'no-disponible';
      } else {
        // Cambiar automáticamente a 'vencido' si la fecha ya pasó
        if (!empty($fecha_vencimiento ) && $fecha_vencimiento <= $fecha_actual && $row['estado'] !== 'vencido') {
          $conexion->query("UPDATE inventario SET estado = 'vencido' WHERE id_Ingrediente = $id");
          $row['estado'] = 'vencido';
        }

        // Cambiar automáticamente a 'agotado' si cantidad == 0
        if ($cantidad == 0 && $row['estado'] !== 'agotado') {
          $conexion->query("UPDATE inventario SET estado = 'agotado' WHERE id_Ingrediente = $id");
          $row['estado'] = 'agotado';
        }

        // Cambiar automáticamente a 'activo' si estado era 'agotado' y cantidad > 0
        if ($cantidad > 0 && $row['estado'] === 'agotado') {
          $conexion->query("UPDATE inventario SET estado = 'activo' WHERE id_Ingrediente = $id");
          $row['estado'] = 'activo';
        }

        // Aplicar clase según estado final
        if ($row['estado'] === 'vencido') {
          $clase_fila = 'vencido';
        } elseif ($row['estado'] === 'agotado') {
          $clase_fila = 'agotado';
        } elseif ($cantidad <= $cantidad_minima) {
          $clase_fila = 'bajo-stock';
        } elseif ($row['estado'] === 'activo') {
          $clase_fila = 'activo';
        } else {
          $clase_fila = '';
        }
      }
      if (isset($contador[$clase_fila])) {
        $contador[$clase_fila]++;
      }
    }
    ?>

      <tr class="<?= $clase_fila ?>">
        <td><?= htmlspecialchars($row['nombre']) ?></td>
        <td><?= number_format($row['cantidad'], 2) ?></td>
        <td><?= number_format($row['cantidad_minima'], 2) ?></td>
        <td><?= htmlspecialchars($row['unidad_medida']) ?></td>
        <td><?= htmlspecialchars($row['costo_unitario']) ?></td>
        <td><?= number_format($row['cantidad'] * $row['costo_unitario'], 2) ?></td>
        <td><?= htmlspecialchars($row['categoria']) ?></td>
        <td><?= $row['fecha_vencimiento'] ?? '—' ?></td>
        <td><?= $row['lote'] ?? '—' ?></td>
        <td><div class="scroll-text"><?= nl2br(htmlspecialchars($row['descripcion'])) ?></div></td>
        <td><?= htmlspecialchars($row['ubicacion_almacen']) ?></td>
        <td><?= ucfirst($row['estado']) ?></td>
        <td><?= htmlspecialchars($row['proveedor']) ?></td>
        <td><?= $row['ultima_actualizacion'] ?></td>
        <td>
      
      <script>
function recalcularTotal(elemento) {
  const fila = elemento.parentElement;
  const cantidad = parseFloat(fila.children[1].innerText.replace(',', '.')) || 0;
  const costo = parseFloat(fila.children[4].innerText.replace(',', '.')) || 0;
  const total = (cantidad * costo).toFixed(2);
  fila.children[5].innerText = total;
}
</script>


<a href="#modalDetalle<?= $row['id_Ingrediente'] ?>" class="btn-action btn-info" data-bs-toggle="modal">
  <i class="fas fa-eye"></i> Ver detalle
</a>

<!-- Modal Detalle Ingrediente -->
<!-- MODAL DE DETALLE MEJORADO -->
<div class="modal fade" id="modalDetalle<?= $row['id_Ingrediente'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title w-100 text-center"><?= htmlspecialchars($row['nombre']) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">

        <!-- Imagen centrada -->
        <?php if (!empty($row['foto']) && file_exists("../uploads/" . $row['foto'])): ?>
  <img 
  src="../uploads/<?= htmlspecialchars($row['foto']) ?>" 
  alt="Imagen del ingrediente" 
  class="img-fluid rounded border mb-4"
  style="max-height: 280px;">
<?php else: ?>
  <div class="text-muted text-center">
    <p><strong>Imagen no disponible</strong></p>
    <i class="fas fa-image fa-3x text-secondary"></i>
  </div>
<?php endif; ?>

        <!-- Descripción -->
        <div class="mb-4 text-start">
          <h6><strong>Descripción:</strong></h6>
          <p class="border rounded p-2 bg-light" style="white-space: pre-wrap; max-height: 200px; overflow-y: auto;">
            <?= nl2br(htmlspecialchars($row['descripcion'])) ?>
          </p>
        </div>

        <!-- Detalles -->
        <div class="row text-start">
          <div class="col-md-6 mb-2"><strong>Costo unitario:</strong> $<?= number_format($row['costo_unitario'], 2) ?></div>
          <div class="col-md-6 mb-2"><strong>Valor total:</strong> $<?= number_format($row['cantidad'] * $row['costo_unitario'], 2) ?></div>
          <div class="col-md-6 mb-2"><strong>Fecha de vencimiento:</strong> <?= $row['fecha_vencimiento'] ?></div>
          <div class="col-md-6 mb-2"><strong>Lote:</strong> <?= $row['lote'] ?></div>
          <div class="col-md-6 mb-2"><strong>Categoría:</strong> <?= $row['categoria'] ?></div>
          <div class="col-md-6 mb-2"><strong>Unidad de medida:</strong> <?= $row['unidad_medida'] ?></div>
          <div class="col-md-6 mb-2"><strong>Cantidad:</strong> <?= $row['cantidad'] ?></div>
          <div class="col-md-6 mb-2"><strong>Cantidad mínima:</strong> <?= $row['cantidad_minima'] ?></div>
          <div class="col-md-6 mb-2"><strong>Estado:</strong> <?= ucfirst($row['estado']) ?></div>
          <div class="col-md-6 mb-2"><strong>Ubicación de almacén:</strong> <?= $row['ubicacion_almacen'] ?></div>
          <div class="col-md-6 mb-2"><strong>Última actualización:</strong> <?= $row['ultima_actualizacion'] ?></div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>





     <!-- Botón que activa el modal DE EDICION -->

        <a href="#" class="btn-action btn-edit"
   data-id="<?= $row['id_Ingrediente'] ?>"
   data-nombre="<?= htmlspecialchars($row['nombre']) ?>"
   data-cantidad="<?= $row['cantidad'] ?>"
   data-cantidad-minima="<?= $row['cantidad_minima'] ?>"
   data-unidad="<?= $row['unidad_medida'] ?>"
   data-costo="<?= $row['costo_unitario'] ?>"
   data-categoria="<?= $row['categoria'] ?>"
   data-fecha-ingreso="<?= $row['fecha_ingreso'] ?>"
   data-vencimiento="<?= $row['fecha_vencimiento'] ?>"
   data-lote="<?= $row['lote'] ?>"
   data-descripcion="<?= $row['descripcion'] ?>"
   data-ubicacion="<?= $row['ubicacion_almacen'] ?>"
   data-estado="<?= $row['estado'] ?>"
   data-proveedor="<?= $row['proveedor'] ?>"
   data-foto="<?= $row['foto'] ?>"
   title="Editar">
   <i class="fas fa-pen"></i> Editar
</a>
          <a href="../modelo/Eliminar_Ingredientes.php?id=<?= $row['id_Ingrediente'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar este ingrediente? Esta acción no se puede deshacer.')" title="Eliminar">
            <i class="fas fa-trash"></i> Eliminar
          </a>  

          <!-- MODAL DE DETALLE -->
<div class="modal fade" id="modal<?= $row['id_Ingrediente'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-custom-bg">
    <div class="modal-content">
      <div class="modal-body text-center p-4">

        <!-- Nombre del insumo centrado -->
        <h4 class="mb-4"><?= htmlspecialchars($row['nombre']) ?></h4>

       <!-- Imagen del insumo -->
<?php if (!empty($row['foto']) && file_exists("../uploads/" . $row['foto'])): ?>
  <img 
    src="../uploads/<?= htmlspecialchars($row['foto']) ?>" 
    alt="Imagen del ingrediente"
    class="img-fluid rounded border mb-4 d-block mx-auto"
    style="max-height: 280px;">
<?php else: ?>
  <p class="text-muted text-center">No se adjuntó imagen o la imagen no se encontró.</p>
<?php endif; ?>
        <!-- Descripción -->
        <div class="text-start mb-4">
          <h6><strong>Descripción:</strong></h6>
          <p><?= nl2br(htmlspecialchars($row['descripcion'])) ?></p>
        </div>

        <!-- Otros detalles -->
        <div class="text-start">
          <p><strong>Costo Unitario:</strong> $<?= htmlspecialchars($row['costo_unitario']) ?></p>
          <p><strong>Valor Total:</strong> $<?= number_format($row['cantidad'] * $row['costo_unitario'], 2) ?></p>
          <p><strong>Fecha de Vencimiento:</strong> <?= $row['fecha_vencimiento'] ?: '—' ?></p>
          <p><strong>Categoría:</strong> <?= htmlspecialchars($row['categoria']) ?></p>
          <p><strong>Proveedor:</strong> <?= htmlspecialchars($row['proveedor']) ?></p>
          <p><strong>Estado:</strong> <?= ucfirst($row['estado']) ?></p>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

    </td>
    </td>

      </tr>
      <?php endwhile; ?>

  </tbody>
  <div class="estado-resumen mb-3" style="display: flex; gap: 2rem; flex-wrap: wrap; font-weight: bold;">
  <div style="color: #c62828;">🟥 Vencidos: <?= $contador['vencido'] ?></div>
  <div style="color: #616161;">⬛ Agotados: <?= $contador['agotado'] ?></div>
  <div style="color: #fbc02d;">🟨 Bajo stock: <?= $contador['bajo-stock'] ?></div>
  <div> Activos: <?= $contador['activo'] ?></div>
  <div style="color: #546e7a;">⬜ No disponibles: <?= $contador['no-disponible'] ?></div>
</div>

  <style>
.vencido {
  background-color: #d32f2f !important;
  color: white !important;
}
.agotado {
  background-color:rgba(0, 0, 0, 0.52) !important;
  color: white !important;
}
.no-disponible {
  background-color: #b0bec5 !important;
  color: black !important;
}
.activo {

}
.bajo-stock {
  background-color: #fff176 !important;
  color: black !important;
}
</style>
</table>

<script>
function filtrarPorEstado() {
  const estado = document.getElementById('filtroEstado').value;
  const filas = document.querySelectorAll('#tablaInventario tbody tr');
  filas.forEach(fila => {
    if (!estado || fila.classList.contains(estado)) {
      fila.style.display = '';
    } else {
      fila.style.display = 'none';
    }
  });
}
</script>
<!--============================================================================================================================ -->


</div>
<script>
let tabla;
$(document).ready(function () {
  if (!$.fn.DataTable.isDataTable('#tablaInventario')) {
    tabla = $('#tablaInventario').DataTable({
      dom: 'Bfrtip',
      scrollX: true,
      buttons: [
        {
          extend: 'excelHtml5',
          title: 'Inventario',
          className: 'btn btn-success',
          text: '📊 Excel'
        },
        {
          extend: 'pdfHtml5',
          title: 'Inventario de Ingredientes',
          orientation: 'landscape',
          pageSize: 'A4',
          exportOptions: {
            columns: ':not(:last-child)' // ocultar la columna de acciones
          },
          customize: function (doc) {
             const now = new Date();
             const fecha = now.toLocaleDateString('es-ES');
             const hora = now.toLocaleTimeString('es-ES');
             const fechaHora = `Fecha del reporte: ${fecha} - ${hora}`;
            doc.styles.tableHeader.fillColor = '#343a40';
            doc.styles.tableHeader.color = '#ffffff';
            doc.styles.tableHeader.alignment = 'center';
            doc.defaultStyle.fontSize = 7;
            doc.styles.header = {
              fontSize: 14,
              bold: true
            };

            const body = doc.content[1].table.body;
const colCount = body[0].length;
doc.content[1].table.widths = Array(colCount).fill('*');


            doc.content[1].alignment = 'center';

            doc.content[1].layout = {
              paddingLeft: () => 2,
              paddingRight: () => 2,
              paddingTop: () => 2,
              paddingBottom: () => 2
            };

            doc.content.unshift({
          text: fechaHora,
          alignment: 'center',
          margin: [0, 0, 0, 5],
          fontSize: 8
           });

            doc.content.unshift({
              text: 'Reporte de Inventario de Ingredientes',
              style: 'header',
              alignment: 'center',
              margin: [0, 0, 0, 10]
            });
          
          
          
          },

          
          className: 'btn btn-danger',
          text: '📄 PDF'
        },
        {
          extend: 'print',
          className: 'btn btn-secondary',
          text: '🖨 Imprimir',
          exportOptions: {
            columns: ':not(:last-child)'
          }
        }
      ],
      language: {
  url: '../assets/datatables/i18n/es-ES.json'

      },
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
      pageLength: 25
    });

    // Filtros personalizados
    $('#filtroCategoria').on('change', function () {
      let val = $(this).val();
      if (val === 'otro') {
        tabla.column(3).search('^(?!Verduras|Frutas|Lácteos|Carnes|Cereales y granos|Panadería|Enlatados|Especias|Snacks|Bebidas).*$', true, false).draw();
      } else {
        tabla.column(3).search(val).draw();
      }
    });

    $('#filtroUnidad').on('change', function () {
      let val = $(this).val();
      if (val === 'otro') {
        tabla.column(2).search('^(?!kg|g|l|ml|unidades).*$', true, false).draw();
      } else {
        tabla.column(2).search(val).draw();
      }
    });

    $('#exportarBtn').on('click', function () {
      tabla.button('.buttons-excel').trigger();
    });
  }
});
</script>


<!-- MODAL DE EDICION -->
<div id="modal-editar" class="modal">
  <div class="modal-contenido">
    <span class="cerrar" onclick="cerrarModalEditar()">&times;</span>
    <h2>Editar Ingrediente</h2>

    <form id="form-editar" action="../modelo/actualizar_ingredientes.php" method="POST" enctype="multipart/form-data">

      <input type="hidden" name="id" id="edit_id">

      <label for="edit_nombre">Nombre del ingrediente</label>
      <input type="text" id="edit_nombre" name="nombre" required maxlength="100">

      <label for="edit_cantidad">Cantidad</label>
      <input type="number" id="edit_cantidad" name="cantidad" step="0.01" required>

      <label for="edit_cantidad_minima">Cantidad mínima</label>
      <input type="number" id="edit_cantidad_minima" name="cantidad_minima" step="0.01" min="0" required>

      <label for="edit_unidad_medida">Unidad de medida</label>
      <select id="edit_unidad_medida" name="unidad_medida" onchange="togglePersonalizado('edit_unidad_medida','edit_unidad_personalizada')" required>
        <option value="">Seleccione unidad</option>
        <option value="kg">Kilogramos</option>
        <option value="g">Gramos</option>
        <option value="l">Litros</option>
        <option value="ml">Mililitros</option>
        <option value="unidades">Unidades</option>
        <option value="otro">Otra...</option>
      </select>
      <input type="text" id="edit_unidad_personalizada" name="unidad_personalizada" placeholder="Unidad personalizada" style="display:none;" maxlength="50">

      <label for="edit_costo_unitario">Costo unitario ($)</label>
      <input type="number" id="edit_costo_unitario" name="costo_unitario" step="0.01" min="0" required>

      <label for="edit_categoria">Categoría</label>
      <select id="edit_categoria" name="categoria" onchange="togglePersonalizado('edit_categoria','edit_categoria_personalizada')" required>
        <option value="">Seleccione categoría</option>
        <option value="Verduras">Verduras</option>
        <option value="Frutas">Frutas</option>
        <option value="Lácteos">Lácteos</option>
        <option value="Carnes">Carnes</option>
        <option value="Cereales y granos">Cereales y granos</option>
        <option value="Panadería">Panadería</option>
        <option value="Enlatados">Enlatados</option>
        <option value="Especias">Especias</option>
        <option value="Snacks">Snacks</option>
        <option value="Bebidas">Bebidas</option>
        <option value="otro">Otra...</option>
      </select>
      <input type="text" id="edit_categoria_personalizada" name="categoria_personalizada" placeholder="Categoría personalizada" style="display:none;" maxlength="50">

      <label for="edit_fecha_ingreso">Fecha de ingreso</label>
      <input type="text" id="edit_fecha_ingreso" name="fecha_ingreso" readonly>

      <label for="edit_fecha_vencimiento">Fecha de vencimiento</label>
      <input 
  type="date" 
  name="fecha_vencimiento" 
  id="edit_fecha_vencimiento"
  min="<?= date('Y-m-d'); ?>" 
  required
>

      <label for="edit_lote">Lote</label>
      <input type="text" id="edit_lote" name="lote" maxlength="100">

      <label for="edit_descripcion">Descripción</label>
      <textarea id="edit_descripcion" name="descripcion" rows="3"></textarea>

      <label for="edit_ubicacion_almacen">Ubicación en almacén</label>
      <input type="text" id="edit_ubicacion_almacen" name="ubicacion_almacen" maxlength="100">

      <label for="edit_estado">Estado</label>
      <select id="edit_estado" name="estado" required>
        <option value="activo">Activo</option>
        <option value="no disponible">No disponible</option>
      </select>

      <label for="edit_proveedor">Proveedor</label>
      <input type="text" id="edit_proveedor" name="proveedor" maxlength="100">

      <label for="edit_foto">Foto del ingrediente</label>
      <input type="file" id="edit_foto" name="foto" accept="image/*">
      <img id="edit_preview" src="#" alt="Vista previa" style="display:none; max-width:200px; margin-top:10px;" />

      <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
  <button type="button" onclick="cerrarModalEditar()" style="background-color: #ccc; color: #333; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
    Cancelar
  </button>
  <button type="submit" style="background-color: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;" onclick="return confirm('¿Estás seguro de que deseas Editar Este ingrediente?.')" title="Editar">
    Guardar Cambios
  </button>
</div>

    </form>
  </div>
</div>
<!--==================================================================================== -->

<!-- ESTILOS DE EL MODAL -->
<?php if (isset($_GET['actualizado']) && $_GET['actualizado'] == 1): ?>
  <script>
    $(document).ready(function() {
      alert("✅ Ingrediente actualizado correctamente.");
    });
  </script>
<?php endif; ?>
<script>
function cerrarModal() {
  $('#modalEditarIngrediente').hide();
}

$('.btn-edit').on('click', function(e) {
  e.preventDefault();
  const btn = $(this);
  $('#edit_id').val(btn.data('id'));
  $('#edit_nombre').val(btn.data('nombre'));
  $('#edit_cantidad').val(btn.data('cantidad'));
  $('#edit_unidad').val(btn.data('unidad'));
  $('#edit_categoria').val(btn.data('categoria'));
  $('#edit_vencimiento').val(btn.data('vencimiento'));
  $('#edit_lote').val(btn.data('lote'));
  $('#edit_estado').val(btn.data('estado'));
  $('#modalEditarIngrediente').fadeIn();
});
</script>
<!-- CARGA EL REGISTRO QUE SELECCIONASTE PARA EDITARLO -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".btn-edit").forEach(btn => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const data = this.dataset;

      document.getElementById("edit_id").value = data.id || "";
      document.getElementById("edit_nombre").value = data.nombre || "";
      document.getElementById("edit_cantidad").value = data.cantidad || "";
      document.getElementById("edit_cantidad_minima").value = data.cantidadMinima || "";
      document.getElementById("edit_unidad_medida").value = data.unidad || "";
      document.getElementById("edit_unidad_personalizada").value = data.unidadPersonalizada || "";
      document.getElementById("edit_costo_unitario").value = data.costo || "";
      document.getElementById("edit_categoria").value = data.categoria || "";
      document.getElementById("edit_categoria_personalizada").value = data.categoriaPersonalizada || "";
      document.getElementById("edit_fecha_ingreso").value = data.fechaIngreso || "";
      document.getElementById("edit_fecha_vencimiento").value = data.vencimiento || "";
      document.getElementById("edit_lote").value = data.lote || "";
      document.getElementById("edit_descripcion").value = data.descripcion || "";
      document.getElementById("edit_ubicacion_almacen").value = data.ubicacion || "";
      document.getElementById("edit_estado").value = data.estado || "";
      document.getElementById("edit_proveedor").value = data.proveedor || "";

      // Vista previa de la imagen
      const preview = document.getElementById("edit_preview");
      if (data.foto) {
        preview.src = "../ruta/a/imagenes/" + data.foto;
        preview.style.display = "block";
      } else {
        preview.style.display = "none";
        preview.src = "#";
      }

      // Mostrar campos personalizados si aplica
      togglePersonalizado("edit_unidad_medida", "edit_unidad_personalizada");
      togglePersonalizado("edit_categoria", "edit_categoria_personalizada");

      // MUESTRA EL MODAL
      document.getElementById("modal-editar").style.display = "block";
    });
  });
});

// FUNCION DE EL BOTON QUE CIERRA EL MODAL DE EDITAR
function cerrarModalEditar() {
  document.getElementById("modal-editar").style.display = "none";
}

// FUNCION QUE MUESTRA O OCULTA CAMPOS PERSONALIZADOS
function togglePersonalizado(selectId, inputId) {
  const select = document.getElementById(selectId);
  const input = document.getElementById(inputId);
  input.style.display = (select.value === "otro") ? "block" : "none";
}

</script>
<!--==================================================================================== -->
<script>
  document.getElementById('form-editar').addEventListener('submit', function (e) {
    const unidadSelect = document.getElementById('edit_unidad_medida');
    const unidadInput = document.getElementById('edit_unidad_personalizada');

    const categoriaSelect = document.getElementById('edit_categoria');
    const categoriaInput = document.getElementById('edit_categoria_personalizada');

    if (unidadSelect.value === 'otro' && unidadInput.value.trim()) {
      unidadSelect.value = unidadInput.value.trim();
    }

    if (categoriaSelect.value === 'otro' && categoriaInput.value.trim()) {
      categoriaSelect.value = categoriaInput.value.trim();
    }
  });
</script>
<!--==================================================================================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function mostrarNotificaciones() {
  fetch("../modelo/notificaciones_ingredientes.php")
    .then(res => res.json())
    .then(data => {
      if (!data.avisos || data.avisos.length === 0) return;

      const host = document.createElement("div");
      const shadow = host.attachShadow({ mode: "open" });
      document.body.appendChild(host);

      shadow.innerHTML = `
        <style>
          .contenedor {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 999999;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            font-family: Arial, sans-serif;
          }

          .noti {
            background-color: #fff;
            border-left: 5px solid;
            border-radius: 0.5rem;
            padding: 1rem;
            padding-right: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            animation: slide-in 0.4s ease-out;
            position: relative;
            font-size: 14px;
            color: #1f2937;
            max-width: 300px;
          }

          .noti[data-tipo="error"] { border-color: #dc2626; background-color: #fee2e2; }
          .noti[data-tipo="warning"] { border-color: #ca8a04; background-color: #fef3c7; }
          .noti[data-tipo="info"] { border-color: #2563eb; background-color: #dbeafe; }

          .cerrar {
            position: absolute;
            top: 4px;
            right: 8px;
            background: none;
            border: none;
            font-size: 18px;
            color: #555;
            cursor: pointer;
          }

          .icono {
            font-size: 20px;
            margin-right: 0.5rem;
          }

          @keyframes slide-in {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
          }
        </style>
        <div class="contenedor" id="notiCont"></div>
      `;

      const contenedor = shadow.getElementById("notiCont");

      data.avisos.forEach(msg => {
        let tipo = "info";
        if (msg.includes("vencido")) tipo = "error";
        else if (msg.includes("bajo")) tipo = "warning";

        const icon = tipo === "error" ? "❌" : tipo === "warning" ? "⚠️" : "ℹ️";

        const noti = document.createElement("div");
        noti.className = "noti";
        noti.dataset.tipo = tipo;
        noti.innerHTML = `
          <button class="cerrar" onclick="this.parentNode.remove()">×</button>
          <div><span class="icono">${icon}</span>${msg}</div>
        `;

        contenedor.appendChild(noti);
      });

      setTimeout(() => host.remove(), 15000);
    })
    .catch(err => {
      console.error("⚠️ Error cargando notificaciones:", err);
    });
}
document.addEventListener("DOMContentLoaded", mostrarNotificaciones);
</script>


</body>
</html>

<?php
if (isset($stmt) && $stmt instanceof mysqli_stmt) {
  $stmt->close();
}
$conexion->close();
?>
