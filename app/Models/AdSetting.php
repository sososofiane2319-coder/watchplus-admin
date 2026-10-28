<?php
/**
 * AdSetting Model
 * Table: ad_settings — getAll, getByNetwork, save, delete, getActiveAds
 */
class AdSetting {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM ad_settings ORDER BY network_name ASC");
        return $stmt->fetchAll();
    }

    public function getByNetwork($networkName) {
        $stmt = $this->pdo->prepare("SELECT * FROM ad_settings WHERE network_name = ?");
        $stmt->execute([$networkName]);
        return $stmt->fetch();
    }

    public function save($networkName, $settingsJson, $isActive = 0) {
        $existing = $this->getByNetwork($networkName);
        if ($existing) {
            $stmt = $this->pdo->prepare("UPDATE ad_settings SET settings_json = ?, is_active = ? WHERE network_name = ?");
            return $stmt->execute([json_encode($settingsJson), $isActive, $networkName]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO ad_settings (network_name, settings_json, is_active) VALUES (?, ?, ?)");
            return $stmt->execute([$networkName, json_encode($settingsJson), $isActive]);
        }
    }

    public function delete($networkName) {
        $stmt = $this->pdo->prepare("DELETE FROM ad_settings WHERE network_name = ?");
        return $stmt->execute([$networkName]);
    }

    public function getActiveAds() {
        $stmt = $this->pdo->query("SELECT * FROM ad_settings WHERE is_active = 1");
        $results = $stmt->fetchAll();
        foreach ($results as &$row) {
            $row['settings'] = json_decode($row['settings_json'], true);
        }
        return $results;
    }
}
