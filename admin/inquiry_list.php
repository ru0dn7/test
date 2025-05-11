<?php
require_once '../config/database.php';

// 관리자 인증 체크 (실제 구현 시 추가 필요)
session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: login.php');
//     exit;
// }

try {
    // 문의 목록 조회
    $stmt = $pdo->query("
        SELECT * FROM inquiries 
        ORDER BY created_at DESC
    ");
    $inquiries = $stmt->fetchAll();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>문의 관리 - PLUXITY</title>
    <style>
        body {
            font-family: 'Pretendard', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f5f5f5;
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
        .view-btn {
            padding: 6px 12px;
            background: #2E61E6;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9em;
        }
        .view-btn:hover {
            background: #1a4fc7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>문의 관리</h1>
        <table>
            <thead>
                <tr>
                    <th>문의번호</th>
                    <th>이름</th>
                    <th>이메일</th>
                    <th>연락처</th>
                    <th>카테고리</th>
                    <th>제목</th>
                    <th>접수일시</th>
                    <th>상태</th>
                    <th>관리</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inquiries as $inquiry): ?>
                <tr>
                    <td><?php echo htmlspecialchars($inquiry['id']); ?></td>
                    <td><?php echo htmlspecialchars($inquiry['name']); ?></td>
                    <td><?php echo htmlspecialchars($inquiry['email']); ?></td>
                    <td><?php echo htmlspecialchars($inquiry['phone']); ?></td>
                    <td><?php echo htmlspecialchars($inquiry['category']); ?></td>
                    <td><?php echo htmlspecialchars($inquiry['subject']); ?></td>
                    <td><?php echo date('Y-m-d H:i', strtotime($inquiry['created_at'])); ?></td>
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
                    <td>
                        <a href="inquiry_detail.php?id=<?php echo $inquiry['id']; ?>" class="view-btn">상세보기</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 