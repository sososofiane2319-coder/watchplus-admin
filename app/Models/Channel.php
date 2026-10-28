<?php
/**
 * Channel Model
 * Table: channels — getAll (with JOIN), getById, create, update, delete, count
 */
class Channel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT c.*, cat.name as category_name 
            FROM channels c 
            LEFT JOIN categories cat ON c.category_id = cat.id 
            ORDER BY c.sort_order ASC, c.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, cat.name as category_name 
            FROM channels c 
            LEFT JOIN categories cat ON c.category_id = cat.id 
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO channels (name, logo, stream_url, category_id, status, sort_order) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['logo'] ?? null,
            $data['stream_url'],
            $data['category_id'] ?: null,
            $data['status'] ?? 'active',
            $data['sort_order'] ?? 0
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE channels 
            SET name = ?, logo = ?, stream_url = ?, category_id = ?, status = ?, sort_order = ? 
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['logo'] ?? null,
            $data['stream_url'],
            $data['category_id'] ?: null,
            $data['status'] ?? 'active',
            $data['sort_order'] ?? 0,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM channels WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM channels");
        return $stmt->fetch()['total'];
    }

    public function getLatest($limit = 5) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, cat.name as category_name 
            FROM channels c 
            LEFT JOIN categories cat ON c.category_id = cat.id 
            ORDER BY c.created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getActive() {
        $stmt = $this->pdo->query("
            SELECT c.*, cat.name as category_name 
            FROM channels c 
            LEFT JOIN categories cat ON c.category_id = cat.id 
            WHERE c.status = 'active' 
            ORDER BY c.sort_order ASC, c.name ASC
        ");
        return $stmt->fetchAll();
    }
}
