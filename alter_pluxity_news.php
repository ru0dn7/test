<?php
require_once 'config.php';

try {
    // category 컬럼 추가
    $sql = "ALTER TABLE pluxity_news ADD COLUMN category VARCHAR(50) NOT NULL DEFAULT '공지사항' AFTER title";
    $pdo->exec($sql);
    echo "pluxity_news 테이블에 category 컬럼이 성공적으로 추가되었습니다.";
} catch (PDOException $e) {
    echo "오류 발생: " . $e->getMessage();
}
?> 