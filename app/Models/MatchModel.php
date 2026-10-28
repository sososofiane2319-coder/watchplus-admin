<?php
/**
 * MatchModel
 * Table: matches — getAll (sorted by time), getById, create, update, delete, count
 */
class MatchModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM `matches` ORDER BY kickoff_time DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM `matches` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO `matches` (competition_name, home_team_name, home_team_logo, away_team_name, away_team_logo, kickoff_time, status, live_minute, stream_url, broadcaster_name, broadcaster_logo, commentator_name)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['competition_name'],
            $data['home_team_name'],
            $data['home_team_logo'] ?? null,
            $data['away_team_name'],
            $data['away_team_logo'] ?? null,
            $data['kickoff_time'],
            $data['status'] ?? 'upcoming',
            $data['live_minute'] ?? null,
            $data['stream_url'] ?? null,
            $data['broadcaster_name'] ?? null,
            $data['broadcaster_logo'] ?? null,
            $data['commentator_name'] ?? null
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE `matches` SET 
                competition_name = ?, home_team_name = ?, home_team_logo = ?, 
                away_team_name = ?, away_team_logo = ?, kickoff_time = ?, 
                status = ?, live_minute = ?, stream_url = ?, 
                broadcaster_name = ?, broadcaster_logo = ?, commentator_name = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['competition_name'],
            $data['home_team_name'],
            $data['home_team_logo'] ?? null,
            $data['away_team_name'],
            $data['away_team_logo'] ?? null,
            $data['kickoff_time'],
            $data['status'] ?? 'upcoming',
            $data['live_minute'] ?? null,
            $data['stream_url'] ?? null,
            $data['broadcaster_name'] ?? null,
            $data['broadcaster_logo'] ?? null,
            $data['commentator_name'] ?? null,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM `matches` WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM `matches`");
        return $stmt->fetch()['total'];
    }

    public function setLive($id, $liveMinute = null) {
        $stmt = $this->pdo->prepare("UPDATE `matches` SET status = 'live', live_minute = ? WHERE id = ?");
        return $stmt->execute([$liveMinute, $id]);
    }

    public function setFinished($id) {
        $stmt = $this->pdo->prepare("UPDATE `matches` SET status = 'finished', live_minute = NULL WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByStatus($status) {
        $stmt = $this->pdo->prepare("SELECT * FROM `matches` WHERE status = ? ORDER BY kickoff_time ASC");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    public function getUpcomingToday() {
        $stmt = $this->pdo->query("
            SELECT * FROM `matches` 
            WHERE (status = 'upcoming' OR status = 'live') 
            AND DATE(kickoff_time) = CURDATE()
            ORDER BY kickoff_time ASC
        ");
        return $stmt->fetchAll();
    }

    public function getLatest($limit = 5) {
        $stmt = $this->pdo->prepare("SELECT * FROM `matches` ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
