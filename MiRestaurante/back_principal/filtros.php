<?php
function construirFiltroInventario(mysqli $conn, int $usuario_id): mysqli_result {
    $condiciones = ["usuario_id = ?"];
    $parametros = [$usuario_id];
    $tipos = "i";

    $categoriasPredef = ['Verduras', 'Frutas', 'Lácteos', 'Carnes', 'Cereales y granos', 'Panadería', 'Enlatados', 'Especias', 'Snacks', 'Bebidas'];
    $unidadesPredef = ['kg', 'g', 'l', 'ml', 'unidades'];

    if (!empty($_GET['categoria'])) {
        if ($_GET['categoria'] === 'otro') {
            $placeholders = implode(',', array_fill(0, count($categoriasPredef), '?'));
            $condiciones[] = "categoria NOT IN ($placeholders)";
            $tipos .= str_repeat("s", count($categoriasPredef));
            $parametros = array_merge($parametros, $categoriasPredef);
        } else {
            $condiciones[] = "categoria = ?";
            $tipos .= "s";
            $parametros[] = $_GET['categoria'];
        }
    }

    if (!empty($_GET['unidad_medida'])) {
        if ($_GET['unidad_medida'] === 'otro') {
            $placeholders = implode(',', array_fill(0, count($unidadesPredef), '?'));
            $condiciones[] = "unidad_medida NOT IN ($placeholders)";
            $tipos .= str_repeat("s", count($unidadesPredef));
            $parametros = array_merge($parametros, $unidadesPredef);
        } else {
            $condiciones[] = "unidad_medida = ?";
            $tipos .= "s";
            $parametros[] = $_GET['unidad_medida'];
        }
    }

    $sql = "SELECT * FROM inventario";
    if ($condiciones) {
        $sql .= " WHERE " . implode(" AND ", $condiciones);
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($tipos, ...$parametros);
    $stmt->execute();
    return $stmt->get_result();
}
?>
