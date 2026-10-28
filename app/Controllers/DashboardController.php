<?php
/**
 * DashboardController
 * Dashboard statistics (counts + latest items)
 */
class DashboardController {

    public static function index($pdo) {
        require_once __DIR__ . '/../Models/Category.php';
        require_once __DIR__ . '/../Models/Channel.php';
        require_once __DIR__ . '/../Models/Series.php';
        require_once __DIR__ . '/../Models/MatchModel.php';

        $categoryModel = new Category($pdo);
        $channelModel = new Channel($pdo);
        $seriesModel = new Series($pdo);
        $matchModel = new MatchModel($pdo);

        $stats = [
            'categories' => $categoryModel->count(),
            'channels' => $channelModel->count(),
            'series' => $seriesModel->count(),
            'matches' => $matchModel->count(),
        ];

        $latestChannels = $channelModel->getLatest(5);
        $latestSeries = $seriesModel->getLatest(5);
        $todayMatches = $matchModel->getUpcomingToday();

        return compact('stats', 'latestChannels', 'latestSeries', 'todayMatches');
    }
}
