<?php
// 데이터베이스 연결 설정
// mycafe24 서버용
define('DB_HOST', 'localhost');  
define('DB_USER', 'kkwoou');
define('DB_PASS', 'qlslWkd1!');
define('DB_NAME', 'kkwoou');

// 로컬 서버용
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'pluxity_db');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        )
    );
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?> 