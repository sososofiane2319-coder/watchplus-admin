<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WATCH PLUS Admin</title>
    <meta name="description" content="لوحة تحكم WATCH PLUS لإدارة القنوات والمسلسلات والمباريات">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* ==========================================
           WATCH PLUS Admin — Primary Inline Stylesheet
           Glassmorphism + Luxury Gold Theme
           ========================================== */

        /* --- CSS Custom Properties (Design Tokens) --- */
        :root {
            /* Gold Palette */
            --gold-50: #FDF8EC;
            --gold-100: #FBF0D4;
            --gold-200: #F1D98B;
            --gold-300: #E6C65C;
            --gold-400: #DDB83F;
            --gold-500: #D4AF37;
            --gold-600: #C9941F;
            --gold-700: #9C7A14;

            /* Background & Surface */
            --bg-body: #FAF8F3;
            --bg-card: rgba(255, 255, 255, 0.78);
            --bg-card-solid: #FFFFFF;
            --glass-blur: blur(14px);
            --glass-border: rgba(212, 175, 55, 0.35);

            /* Text */
            --text-primary: #0B1220;
            --text-secondary: #475569;
            --text-muted: #94A3B8;

            /* Semantic Colors */
            --success: #10B981;
            --success-bg: #D1FAE5;
            --danger: #EF4444;
            --danger-bg: #FEE2E2;
            --warning: #F59E0B;
            --warning-bg: #FEF3C7;
            --info: #3B82F6;
            --info-bg: #DBEAFE;

            /* Gradients */
            --gradient-side: linear-gradient(160deg, #0B1220 0%, #1A2333 60%, #0B1220 100%);
            --gradient-btn: linear-gradient(135deg, #E6C65C, #C9941F);
            --gradient-btn-h: linear-gradient(135deg, #D4AF37, #9C7A14);

            /* Borders & Focus */
            --border: #EFE6CC;
            --border-focus: #DDB83F;

            /* Sizes */
            --sidebar-w: 260px;
            --topbar-h: 64px;
            --radius: 14px;
            --radius-sm: 10px;
            --radius-xs: 6px;

            /* Shadows */
            --shadow-card: 0 2px 16px rgba(212, 175, 55, 0.08), 0 1px 4px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 12px 40px rgba(0, 0, 0, 0.12);

            /* Transition */
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* --- Reset & Base --- */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            background-image:
                radial-gradient(ellipse at 10% 20%, rgba(241, 217, 139, 0.35) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(241, 217, 139, 0.25) 0%, transparent 50%);
            background-attachment: fixed;
            color: var(--text-primary);
            min-height: 100vh;
            direction: rtl;
        }

        a { text-decoration: none; color: inherit; }

        /* --- Scrollbar --- */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--gold-50); }
        ::-webkit-scrollbar-thumb { background: var(--gold-200); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold-300); }

        /* --- Dashboard Wrapper --- */
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--gradient-side);
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s var(--ease);
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 24px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(212, 175, 55, 0.15);
        }

        .sidebar-brand h2 {
            color: var(--gold-400);
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .sidebar-brand span {
            color: var(--text-muted);
            font-size: 0.75rem;
            display: block;
            margin-top: 4px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            list-style: none;
        }

        .sidebar-nav li {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            color: rgba(255, 255, 255, 0.65);
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s var(--ease);
        }

        .nav-link:hover {
            background: rgba(212, 175, 55, 0.1);
            color: var(--gold-400);
        }

        .nav-link.active {
            background: rgba(212, 175, 55, 0.15);
            color: var(--gold-400);
            font-weight: 600;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(212, 175, 55, 0.15);
            text-align: center;
        }

        .sidebar-footer p {
            color: var(--text-muted);
            font-size: 0.7rem;
        }

        /* --- Topbar --- */
        .topbar {
            height: var(--topbar-h);
            background: var(--bg-card);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-bottom: 1px solid var(--glass-border);
            position: fixed;
            top: 0;
            right: var(--sidebar-w);
            left: 0;
            z-index: 99;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            box-shadow: var(--shadow-card);
        }

        .topbar-search {
            position: relative;
            width: 300px;
        }

        .topbar-search input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--gold-50);
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            color: var(--text-primary);
            transition: all 0.2s var(--ease);
            direction: rtl;
        }

        .topbar-search input:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(221, 184, 63, 0.15);
        }

        .topbar-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-btn {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: var(--radius-xs);
            background: var(--gold-50);
            color: var(--text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s var(--ease);
        }

        .topbar-btn:hover {
            background: var(--gold-100);
            color: var(--gold-500);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            background: var(--gold-50);
            cursor: pointer;
            transition: all 0.2s var(--ease);
        }

        .admin-profile:hover {
            background: var(--gold-100);
        }

        .admin-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--gradient-btn);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .admin-info {
            display: flex;
            flex-direction: column;
        }

        .admin-info small {
            color: var(--text-muted);
            font-size: 0.7rem;
        }

        .admin-info span {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .btn-logout {
            padding: 8px 16px;
            border: none;
            border-radius: var(--radius-xs);
            background: var(--danger-bg);
            color: var(--danger);
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s var(--ease);
            font-family: 'Inter', sans-serif;
        }

        .btn-logout:hover {
            background: var(--danger);
            color: white;
        }

        /* --- Main Content --- */
        .main-content {
            margin-right: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 24px;
            min-height: calc(100vh - var(--topbar-h));
            flex: 1;
        }

        /* --- Cards --- */
        .card {
            background: var(--bg-card);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            padding: 24px;
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .card-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* --- Stat Cards --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--bg-card);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s var(--ease), box-shadow 0.2s var(--ease);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-icon.gold { background: var(--gold-100); color: var(--gold-500); }
        .stat-icon.blue { background: var(--info-bg); color: var(--info); }
        .stat-icon.green { background: var(--success-bg); color: var(--success); }
        .stat-icon.purple { background: #EDE9FE; color: #7C3AED; }

        .stat-info h4 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-info p {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-top: 4px;
        }

        /* --- Content Grid --- */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .content-grid.full {
            grid-template-columns: 1fr;
        }

        /* --- Tables --- */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: var(--gold-50);
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            text-align: right;
            border-bottom: 2px solid var(--border);
        }

        table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            font-size: 0.88rem;
            color: var(--text-primary);
            vertical-align: middle;
        }

        table tr:hover td {
            background: var(--gold-50);
        }

        table img.table-img {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-xs);
            object-fit: cover;
            border: 2px solid var(--gold-100);
        }

        /* --- Badges --- */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success { background: var(--success-bg); color: var(--success); }
        .badge-danger { background: var(--danger-bg); color: var(--danger); }
        .badge-warning { background: var(--warning-bg); color: var(--warning); }
        .badge-info { background: var(--info-bg); color: var(--info); }

        .badge-live {
            background: var(--danger);
            color: white;
            animation: pulseLive 1.2s ease-in-out infinite;
        }

        @keyframes pulseLive {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        /* --- Buttons --- */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: var(--radius-xs);
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s var(--ease);
            text-decoration: none;
        }

        .btn-primary {
            background: var(--gradient-btn);
            color: white;
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.3);
        }

        .btn-primary:hover {
            background: var(--gradient-btn-h);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--gold-50);
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--gold-100);
            color: var(--gold-600);
        }

        .btn-danger {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .btn-danger:hover {
            background: var(--danger);
            color: white;
        }

        .btn-success {
            background: var(--success-bg);
            color: var(--success);
        }

        .btn-success:hover {
            background: var(--success);
            color: white;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.78rem;
        }

        .btn-icon {
            width: 34px;
            height: 34px;
            padding: 0;
            justify-content: center;
        }

        /* --- Forms --- */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .form-control {
            width: 100%;
            padding: 11px 16px;
            border: 1px solid var(--border);
            border-radius: var(--radius-xs);
            background: var(--bg-card-solid);
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            color: var(--text-primary);
            transition: all 0.2s var(--ease);
            direction: rtl;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(221, 184, 63, 0.15);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        select.form-control {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23475569' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 12px center;
            padding-left: 32px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-switch {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .form-switch input[type="checkbox"] {
            display: none;
        }

        .form-switch .toggle {
            width: 44px;
            height: 24px;
            background: var(--border);
            border-radius: 12px;
            position: relative;
            transition: background 0.2s var(--ease);
        }

        .form-switch .toggle::after {
            content: '';
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 3px;
            right: 3px;
            transition: transform 0.2s var(--ease);
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }

        .form-switch input:checked + .toggle {
            background: var(--gold-500);
        }

        .form-switch input:checked + .toggle::after {
            transform: translateX(-20px);
        }

        .form-switch span {
            font-size: 0.88rem;
            color: var(--text-secondary);
        }

        /* --- Upload Area --- */
        .upload-area {
            border: 2px dashed var(--border);
            border-radius: var(--radius-sm);
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s var(--ease);
            background: var(--gold-50);
        }

        .upload-area:hover {
            border-color: var(--gold-400);
            background: var(--gold-100);
        }

        .upload-area i {
            font-size: 2rem;
            color: var(--gold-400);
            margin-bottom: 8px;
        }

        .upload-area p {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .upload-area input[type="file"] {
            display: none;
        }

        .upload-preview {
            max-width: 120px;
            max-height: 120px;
            border-radius: var(--radius-xs);
            margin-top: 10px;
            object-fit: cover;
        }

        /* --- Alerts --- */
        .alert {
            padding: 14px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.88rem;
            font-weight: 500;
            animation: slideDown 0.3s var(--ease);
            position: relative;
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background: var(--danger-bg);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert-close {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.6;
        }

        .alert-close:hover {
            opacity: 1;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- Page Header / Breadcrumb --- */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .breadcrumb a {
            color: var(--gold-500);
        }

        .breadcrumb a:hover {
            color: var(--gold-600);
        }

        /* --- Match Cards --- */
        .match-card {
            background: var(--bg-card);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 12px;
            transition: transform 0.2s var(--ease);
        }

        .match-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .match-teams {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .match-team {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            min-width: 80px;
        }

        .match-team img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .match-team span {
            font-size: 0.82rem;
            font-weight: 600;
            text-align: center;
        }

        .match-vs {
            font-size: 1rem;
            font-weight: 800;
            color: var(--gold-500);
        }

        .match-info {
            text-align: center;
        }

        .match-info .competition {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .match-info .time {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .match-actions {
            display: flex;
            gap: 8px;
        }

        /* --- Filters --- */
        .filter-bar {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: var(--bg-card-solid);
            color: var(--text-secondary);
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s var(--ease);
            font-family: 'Inter', sans-serif;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--gold-500);
            color: white;
            border-color: var(--gold-500);
        }

        /* --- Empty State --- */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 16px;
            color: var(--gold-200);
        }

        .empty-state h4 {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        /* --- Ad Network Cards --- */
        .ad-networks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .ad-network-card {
            background: var(--bg-card);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 20px;
            cursor: pointer;
            transition: all 0.2s var(--ease);
            text-align: center;
        }

        .ad-network-card:hover,
        .ad-network-card.selected {
            border-color: var(--gold-500);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
        }

        .ad-network-card.selected {
            background: var(--gold-50);
        }

        .ad-network-card i {
            font-size: 2rem;
            color: var(--gold-500);
            margin-bottom: 10px;
        }

        .ad-network-card h4 {
            font-size: 0.95rem;
            font-weight: 700;
        }

        /* --- Settings Toggle Cards --- */
        .settings-toggles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .toggle-card {
            background: var(--bg-card);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-sm);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .toggle-card .toggle-info h5 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .toggle-card .toggle-info p {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        /* --- Animations --- */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- Responsive --- */
        .sidebar-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border: none;
            background: var(--gold-50);
            color: var(--text-secondary);
            border-radius: var(--radius-xs);
            cursor: pointer;
            font-size: 1.1rem;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .topbar {
                right: 0;
            }

            .main-content {
                margin-right: 0;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .topbar-search {
                width: 180px;
            }

            .admin-info {
                display: none;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* --- Season/Episode Management --- */
        .season-block {
            background: var(--gold-50);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 20px;
            margin-bottom: 16px;
        }

        .season-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .season-header h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gold-600);
        }

        .episode-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            background: var(--bg-card-solid);
            border-radius: var(--radius-xs);
            margin-bottom: 6px;
            border: 1px solid var(--border);
        }

        .episode-row span {
            font-size: 0.85rem;
        }

        /* --- Sidebar overlay for mobile --- */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* --- Danger Zone (Settings) --- */
        .danger-zone {
            border: 2px solid var(--danger);
            border-radius: var(--radius);
            padding: 24px;
            margin-top: 24px;
        }

        .danger-zone h4 {
            color: var(--danger);
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .danger-zone p {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
<div class="dashboard-wrapper">
