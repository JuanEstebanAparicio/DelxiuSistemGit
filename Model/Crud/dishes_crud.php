<?php
$baseDir = dirname(dirname(__DIR__));
require_once($baseDir . '../Model/Entity/dishes.php');
require_once($baseDir . '../Model/Entity/Connection.php');

class DishesCRUD {
	private $pdo;

	public function __construct($pdo = null) {
        if ($pdo === null) {
            $this->pdo = Connection::getConnection();
        } else {
            $this->pdo = $pdo;
        }
    }

	// Create
	public function createDish(dishes $dish) {
		if (empty($dish->getNameDish()) || empty($dish->getPrice()) || empty($dish->getCategory())) {
			throw new Exception("Los campos obligatorios no pueden estar vacíos.");
		}

		$sql = "INSERT INTO platos 
			(name_dish, price, category, description, state, created_at, photo)
			VALUES (?, ?, ?, ?, ?, ?, ?)";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			$dish->getNameDish(),
			$dish->getPrice(),
			$dish->getCategory(),
			$dish->getDescription(),
			$dish->getState(),
			$dish->getCreatedAt(),
			$dish->getPhoto()
		]);

		return true;
	}

	// Update
	public function updateDish(dishes $dish, $id) {
		$sql = "UPDATE platos SET 
			name_dish = ?, 
			price = ?, 
			category = ?, 
			description = ?, 
			state = ?, 
			created_at = ?, 
			photo = ?
			WHERE id = ?";

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			$dish->getNameDish(),
			$dish->getPrice(),
			$dish->getCategory(),
			$dish->getDescription(),
			$dish->getState(),
			$dish->getCreatedAt(),
			$dish->getPhoto(),
			$id
		]);

		return true;
	}

	// Delete
	public function deleteDish($id) {
		$sql = "DELETE FROM platos WHERE id = ?";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$id]);

		return true;
	}

	// Read (opcional: obtener todos los platos)
	public function getAllDishes() {
		$sql = "SELECT * FROM platos";
		$stmt = $this->pdo->query($sql);
		$dishes = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$dishes[] = new dishes(
				$row['id'],
				$row['name_dish'],
				$row['price'],
				$row['category'],
				$row['description'],
				$row['state'],
				$row['created_at'],
				$row['photo']
			);
		}
		return $dishes;
	}
}
?>
