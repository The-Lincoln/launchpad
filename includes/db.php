<?php
require_once __DIR__ . '/config.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;

    if (!is_dir(dirname(DB_PATH))) mkdir(dirname(DB_PATH), 0775, true);

    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');

    migrate($pdo);
    return $pdo;
}

function migrate(PDO $pdo): void {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            role TEXT NOT NULL DEFAULT 'client',
            company TEXT,
            phone TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS pricing_tiers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            service_line TEXT NOT NULL,
            name TEXT NOT NULL,
            price_bdt INTEGER NOT NULL,
            features TEXT NOT NULL,
            is_active INTEGER NOT NULL DEFAULT 1,
            sort_order INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS leads (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT,
            company TEXT,
            service_line TEXT,
            tier_id INTEGER,
            message TEXT,
            status TEXT NOT NULL DEFAULT 'new',
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (tier_id) REFERENCES pricing_tiers(id)
        );

        CREATE TABLE IF NOT EXISTS projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            client_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            service_line TEXT NOT NULL,
            status TEXT NOT NULL DEFAULT 'planning',
            started_at TEXT DEFAULT CURRENT_TIMESTAMP,
            notes TEXT,
            FOREIGN KEY (client_id) REFERENCES users(id)
        );

        CREATE TABLE IF NOT EXISTS proposals (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            lead_id INTEGER,
            client_id INTEGER,
            title TEXT NOT NULL,
            body TEXT NOT NULL,
            amount_bdt INTEGER,
            status TEXT NOT NULL DEFAULT 'draft',
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (lead_id) REFERENCES leads(id),
            FOREIGN KEY (client_id) REFERENCES users(id)
        );
    ");

    $count = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
    if ((int)$count === 0) {
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,'admin')");
        $stmt->execute([
            'SCITBD Admin',
            ADMIN_EMAIL,
            password_hash(DEFAULT_ADMIN_PASS, PASSWORD_DEFAULT)
        ]);
    }

    $pcount = $pdo->query("SELECT COUNT(*) FROM pricing_tiers")->fetchColumn();
    if ((int)$pcount === 0) {
        $seed = [
            ['website', 'Silver', 10000, "Corporate / business site\nCMS integration\nSpeed optimization\nSEO-friendly structure\n5 pages, responsive", 1],
            ['website', 'Platinum', 20000, "E-commerce / LMS / news portal\nCustom CMS\nAdvanced SEO\nPerformance tuning\nPriority support", 2],
            ['indicator', 'Indicator Design – Starter', 15000, "Logic model alignment\nSMART indicator set\nBaseline & target table\nVerification sources", 1],
            ['indicator', 'Indicator Design – Full M&E', 35000, "RACER / SPICED criteria\nQualitative + quantitative mix\nClient accountability framework\nReporting template + training", 2],
        ];
        $stmt = $pdo->prepare("INSERT INTO pricing_tiers (service_line,name,price_bdt,features,sort_order) VALUES (?,?,?,?,?)");
        foreach ($seed as $s) $stmt->execute($s);
    }
}
