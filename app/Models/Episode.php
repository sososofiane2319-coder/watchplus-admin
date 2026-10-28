<?php
/**
 * Episode Model
 * Table: episodes — getBySeasonId, create, update, delete
 */
class Episode {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getBySeasonId($seasonId) {
        $stmt = $this->pdo->prepare("SELECT * FROM episodes WHERE season_id = ? ORDER BY episode_number ASC");
        $stmt->execute([$seasonId]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM episodes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($seasonId, $episodeNumber, $title, $videoUrl) {
        $stmt = $this->pdo->prepare("INSERT INTO episodes (season_id, episode_number, title, video_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$seasonId, $episodeNumber, $title, $videoUrl]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $episodeNumber, $title, $videoUrl) {
        $stmt = $this->pdo->prepare("UPDATE episodes SET episode_number = ?, title = ?, video_url = ? WHERE id = ?");
        return $stmt->execute([$episodeNumber, $title, $videoUrl, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM episodes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
