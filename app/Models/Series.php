<?php
/**
 * Series Model
 * Table: series — getAll (with JOIN), getById, create, update, delete, count
 */
class Series {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT s.*, cat.name as category_name,
                (SELECT COUNT(*) FROM seasons WHERE series_id = s.id) as season_count,
                (SELECT COUNT(*) FROM episodes e JOIN seasons sn ON e.season_id = sn.id WHERE sn.series_id = s.id) as episode_count
            FROM series s 
            LEFT JOIN categories cat ON s.category_id = cat.id 
            ORDER BY s.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, cat.name as category_name 
            FROM series s 
            LEFT JOIN categories cat ON s.category_id = cat.id 
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO series (title, description, release_year, rating, poster, banner, category_id, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['title'],
            $data['description'] ?? null,
            $data['release_year'] ?: null,
            $data['rating'] ?: null,
            $data['poster'] ?? null,
            $data['banner'] ?? null,
            $data['category_id'] ?: null,
            $data['status'] ?? 'active'
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE series 
            SET title = ?, description = ?, release_year = ?, rating = ?, poster = ?, banner = ?, category_id = ?, status = ? 
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['title'],
            $data['description'] ?? null,
            $data['release_year'] ?: null,
            $data['rating'] ?: null,
            $data['poster'] ?? null,
            $data['banner'] ?? null,
            $data['category_id'] ?: null,
            $data['status'] ?? 'active',
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM series WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM series");
        return $stmt->fetch()['total'];
    }

    public function getLatest($limit = 5) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, cat.name as category_name 
            FROM series s 
            LEFT JOIN categories cat ON s.category_id = cat.id 
            ORDER BY s.created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getActive() {
        $stmt = $this->pdo->query("
            SELECT s.*, cat.name as category_name,
                (SELECT COUNT(*) FROM seasons WHERE series_id = s.id) as season_count,
                (SELECT COUNT(*) FROM episodes e JOIN seasons sn ON e.season_id = sn.id WHERE sn.series_id = s.id) as episode_count
            FROM series s 
            LEFT JOIN categories cat ON s.category_id = cat.id 
            WHERE s.status = 'active' 
            ORDER BY s.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getWithSeasonsAndEpisodes($id) {
        $series = $this->getById($id);
        if (!$series) return null;

        $seasonModel = new Season($this->pdo);
        $episodeModel = new Episode($this->pdo);

        $seasons = $seasonModel->getBySeriesId($id);
        foreach ($seasons as &$season) {
            $season['episodes'] = $episodeModel->getBySeasonId($season['id']);
        }
        $series['seasons'] = $seasons;
        return $series;
    }
}
