<?php
require_once '../config/database.php';

// 관리자 인증 체크 (실제 구현 시 추가 필요)
session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: login.php');
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inquiry_list.php');
    exit;
}

if (!isset($_POST['id']) || !isset($_POST['status'])) {
    $_SESSION['error'] = '잘못된 요청입니다.';
    header('Location: inquiry_list.php');
    exit;
}

$allowed_statuses = ['pending', 'processing', 'completed'];
if (!in_array($_POST['status'], $allowed_statuses)) {
    $_SESSION['error'] = '잘못된 상태값입니다.';
    header('Location: inquiry_list.php');
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE inquiries 
        SET status = :status 
        WHERE id = :id
    ");
    
    $stmt->execute([
        'id' => $_POST['id'],
        'status' => $_POST['status']
    ]);

    $_SESSION['success'] = '상태가 성공적으로 업데이트되었습니다.';
    header('Location: inquiry_detail.php?id=' . $_POST['id']);
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = '상태 업데이트 중 오류가 발생했습니다.';
    header('Location: inquiry_detail.php?id=' . $_POST['id']);
    exit;
}
?> 