<?php
require_once('../Model/Entity/Connection.php');
$conexion = Connection::getConnection();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Gestor de Platos</title>
	<link rel="stylesheet" href="../CSS/insumos.css">
	<link rel="stylesheet" href="../CSS/modales.css">
	<link rel="stylesheet" href="../CSS/registroInsumo.css">
	<link rel="stylesheet" href="../CSS/tables.css">
	<style>
	.modal-content { max-height: 90vh; overflow-y: auto; }
	</style>
</head>
<body>

<!-- MODAL para registrar y para editar -->
<div id="formModal" class="modal hidden">
	<div class="modal-content">
		<span class="close" onclick="hideModal('formModal')">&times;</span>
	<h2 id="modalTitle">Registrar Plato</h2>
	<form id="dishForm" action="/Proyecto_de_aula/Controller/store/dishes_add.php" 
		  method="POST" enctype="multipart/form-data">
	<input type="hidden" name="id" id="dish_id">

	<label>Nombre del plato:</label>
	<input type="text" name="nombre" id="nombre" required>

	<label>Categoría:</label>
			<select name="categoria" id="categoria" required>
				<option value="">Seleccione</option>
				<option value="Entrada">Entrada</option>
	<option value="Plato Fuerte">Plato Fuerte</option>
	<option value="Bebida">Bebida</option>
	<option value="Postre">Postre</option>
	</select>

	<label>Precio:</label>
	<input type="number" step="0.01" name="precio" id="precio" required>

	<label>Descripción:</label>
	<textarea name="descripcion" id="descripcion"></textarea>

	<label>Estado:</label>
			<select name="estado" id="estado" required>
				<option value="Activo">Activo</option>
				<option value="Inactivo">Inactivo</option>
	</select>

	<label>Foto:</label>
	<input type="file" name="foto" id="foto" accept="image/*">

	<button type="submit" class="modal-btn" id="submitBtn">Registrar Plato</button>
		</form>
	</div>
</div>

<script src="../JS/animations/modales.js"></script>

<div class="content">
	<h2>Gestor de Platos</h2>
	<button onclick="newDish()" class="modal-btn">+ Registrar Plato</button>
</div>
<div class="content">
	<?php if (isset($_GET['success'])): ?>
		<?php if ($_GET['success'] == 1): ?>
			<p class="success-msg">✅ Plato registrado correctamente.</p>
		<?php elseif ($_GET['success'] == 2): ?>
			<p class="success-msg">✏️ Plato actualizado correctamente.</p>
		<?php elseif ($_GET['success'] == 3): ?>
			<p class="success-msg">🗑️ Plato eliminado correctamente.</p>
		<?php endif; ?>
	<?php endif; ?>
</div>
	<div class="table-container">
		<table class="styled-table">
			<thead>
				<tr>
					<th>Nombre</th><th>Categoría</th><th>Precio</th><th>Estado</th><th>Descripción</th><th>Foto</th><th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($row = $resultado->fetch()): ?>
					<tr>
						<td><?= $row['nombre']; ?></td>
						<td><?= $row['categoria']; ?></td>
			<td><?= $row['precio']; ?></td>
			<td><?= $row['estado']; ?></td>
			<td><?= $row['descripcion']; ?></td>
						<td>
							<?php if (!empty($row['foto'])): ?>
								<img src="<?= $row['foto']; ?>" alt="Foto" style="width:50px;height:50px;object-fit:cover;">
							<?php else: ?>
								Sin foto
							<?php endif; ?>
						</td>
						<td>
							<a href="javascript:void(0)" 
					  onclick='editDish(<?= json_encode($row); ?>)' 
					  class="btn btn-edit">✏️ Editar</a>
				  <a href="/Proyecto_de_aula/Controller/store/dishes_delete.php?id=<?= $row['id']; ?>" 
				onclick="return confirm('¿Eliminar plato?');"
				class="btn btn-delete">🗑️ Eliminar</a>
			</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<script>
function newDish() {
	document.getElementById("dishForm").reset();
	document.getElementById("dish_id").value = "";
	document.getElementById("modalTitle").textContent = "Registrar Plato";
	document.getElementById("submitBtn").textContent = "Registrar Plato";
	document.getElementById("dishForm").action = "/Proyecto_de_aula/Controller/store/dishes_add.php";
	showModal("formModal");
}

function editDish(data) {
	document.getElementById("dish_id").value = data.id;
	document.getElementById("nombre").value = data.nombre;
	document.getElementById("categoria").value = data.categoria;
	document.getElementById("precio").value = data.precio;
	document.getElementById("descripcion").value = data.descripcion;
	document.getElementById("estado").value = data.estado;
	document.getElementById("modalTitle").textContent = "Editar Plato";
	document.getElementById("submitBtn").textContent = "Actualizar Plato";
	document.getElementById("dishForm").action = "/Proyecto_de_aula/Controller/store/dishes_edit.php";
	showModal("formModal");
}
</script>

</body>
</html>
