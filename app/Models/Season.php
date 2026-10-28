<?php
/**
 * Season Model
 * Table: seasons — getBySeriesId, create, update, delete
 */
class Season {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getBySeriesId($seriesId) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, 
                (SELECT COUNT(*) FROM episodes WHERE season_id = s.id) as episode_count
            FROM seasons s 
            WHERE s.series_id = ? 
            ORDER BY s.season_number ASC
        ");
        $stmt->execute([$seriesId]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM seasons WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($seriesId, $seasonNumber, $releaseYear = null) {
        $stmt = $this->pdo->prepare("INSERT INTO seasons (series_id, season_number, release_year) VALUES (?, ?, ?)");
        $stmt->execute([$seriesId, $seasonNumber, $releaseYear]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $seasonNumber, $releaseYear = null) {
        $stmt = $this->pdo->prepare("UPDATE seasons SET season_number = ?, release_year = ? WHERE id = ?");
        return $stmt->execute([$seasonNumber, $releaseYear, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM seasons WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
