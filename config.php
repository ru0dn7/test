<?php
// 오류 표시 설정
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
// define('DB_NAME', 'pluxity_board');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    );
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // 연결 테스트
    $pdo->query("SELECT 1");
    
} catch(PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    
    // 개발 환경에서만 상세 에러 표시
    if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') {
        die("Connection failed: " . $e->getMessage());
    } else {
        die("데이터베이스 연결에 실패했습니다. 잠시 후 다시 시도해주세요.");
    }
}
?> 