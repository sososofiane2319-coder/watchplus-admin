<?php
/**
 * Category Model
 * Table: categories — getAll, getById, create, update, delete, count
 */
class Category {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name, $type = 'general') {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name, type) VALUES (?, ?)");
        return $stmt->execute([$name, $type]);
    }

    public function update($id, $name, $type) {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = ?, type = ? WHERE id = ?");
        return $stmt->execute([$name, $type, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM categories");
        return $stmt->fetch()['total'];
    }
}
