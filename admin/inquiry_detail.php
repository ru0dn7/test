<?php
require_once '../config/database.php';

// 관리자 인증 체크 (실제 구현 시 추가 필요)
session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: login.php');
//     exit;
// }

if (!isset($_GET['id'])) {
    header('Location: inquiry_list.php');
    exit;
}

try {
    // 문의 상세 정보 조회
    $stmt = $pdo->prepare("
        SELECT * FROM inquiries 
        WHERE id = :id
    ");
    $stmt->execute(['id' => $_GET['id']]);
    $inquiry = $stmt->fetch();

    if (!$inquiry) {
        header('Location: inquiry_list.php');
        exit;
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>문의 상세 - PLUXITY</title>
    <style>
        body {
            font-family: 'Pretendard', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table th, .info-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .info-table th {
            width: 150px;
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .content-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
            white-space: pre-wrap;
        }
        .button-group {
            margin-top: 20px;
            text-align: right;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }
        .btn-primary {
            background: #2E61E6;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-processing {
            background: #cce5ff;
            color: #004085;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>문의 상세</h1>
        <table class="info-table">
            <tr>
                <th>문의번호</th>
                <td><?php echo htmlspecialchars($inquiry['id']); ?></td>
            </tr>
            <tr>
                <th>접수일시</th>
                <td><?php echo date('Y-m-d H:i:s', strtotime($inquiry['created_at'])); ?></td>
            </tr>
            <tr>
                <th>상태</th>
                <td>
                    <span class="status status-<?php echo $inquiry['status']; ?>">
                        <?php
                        switch($inquiry['status']) {
                            case 'pending':
                                echo '대기중';
                                break;
                            case 'processing':
                                echo '처리중';
                                break;
                            case 'completed':
                                echo '완료';
                                break;
                        }
                        ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th>이름</th>
                <td><?php echo htmlspecialchars($inquiry['name']); ?></td>
            </tr>
            <tr>
                <th>이메일</th>
                <td><?php echo htmlspecialchars($inquiry['email']); ?></td>
            </tr>
            <tr>
                <th>연락처</th>
                <td><?php echo htmlspecialchars($inquiry['phone']); ?></td>
            </tr>
            <tr>
                <th>회사명</th>
                <td><?php echo htmlspecialchars($inquiry['company'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <th>카테고리</th>
                <td><?php echo htmlspecialchars($inquiry['category']); ?></td>
            </tr>
            <tr>
                <th>제목</th>
                <td><?php echo htmlspecialchars($inquiry['subject']); ?></td>
            </tr>
            <tr>
                <th>내용</th>
                <td>
                    <div class="content-box">
                        <?php echo nl2br(htmlspecialchars($inquiry['content'])); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>동의사항</th>
                <td>
                    개인정보처리방침: <?php echo $inquiry['privacy_agree'] ? '동의' : '미동의'; ?><br>
                    마케팅 정보 수신: <?php echo $inquiry['marketing_agree'] ? '동의' : '미동의'; ?>
                </td>
            </tr>
        </table>
        <div class="button-group">
            <a href="inquiry_list.php" class="btn btn-secondary">목록으로</a>
            <form method="POST" action="update_status.php" style="display: inline;">
                <input type="hidden" name="id" value="<?php echo $inquiry['id']; ?>">
                <input type="hidden" name="status" value="processing">
                <button type="submit" class="btn btn-primary" onclick="return confirm('상태를 처리중으로 변경하시겠습니까?')">처리중으로 변경</button>
            </form>
            <form method="POST" action="update_status.php" style="display: inline;">
                <input type="hidden" name="id" value="<?php echo $inquiry['id']; ?>">
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="btn btn-primary" onclick="return confirm('상태를 완료로 변경하시겠습니까?')">완료로 변경</button>
            </form>
        </div>
    </div>
</body>
</html> 