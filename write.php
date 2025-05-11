<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = $_POST['title'] ?? '';
        $category = $_POST['category'] ?? '공지사항';
        $content = $_POST['content'] ?? '';
        $image = null;

        // 썸네일 이미지 처리
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_extension = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_path)) {
                $image = $upload_path;
            }
        }

        // 데이터베이스에 저장
        $sql = "INSERT INTO pluxity_news (title, category, content, image, created_at, status) 
                VALUES (:title, :category, :content, :image, NOW(), 'active')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            header('Location: news.php');
            exit;
        }
    } catch (PDOException $e) {
        $error = "데이터베이스 오류: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시물 작성 - PLUXITY</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/pluxity-board.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1><a href="index.php"><span class="hidden">PLUXITY</span><img src="images/logo.png" alt="PLUXITY"></a></h1>
            <nav>
                <h2 class="hidden">메인 네비게이션</h2>
                <ul>
                    <li><a href="about.php">ABOUT</a></li>
                    <li><a href="achievements.php">Achievements</a></li>
                    <li><a href="#">PLUG</a></li>
                    <li><a href="#">CAREER</a></li>
                    <li><a href="news.php" class="active">NEWS</a></li>
                    <li><a href="contact_us.php">CONTACT US</a></li>
                </ul>
            </nav>
            <ul class="lang">
                <li class="on"><a href="#">KR</a></li>
                <li><a href="#">EN</a></li>
            </ul>
        </div>
    </header>

    <main class="news-write">
        <h1>게시물 작성</h1>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="write-form">
            <div class="form-group">
                <label for="category">카테고리</label>
                <select name="category" id="category" required>
                    <option value="공지사항">공지사항</option>
                    <option value="보도자료">보도자료</option>
                    <option value="기업뉴스">기업뉴스</option>
                </select>
            </div>

            <div class="form-group">
                <label for="title">제목</label>
                <input type="text" name="title" id="title" required>
            </div>

            <div class="form-group">
                <label for="content">내용</label>
                <textarea name="content" id="content" rows="10" required></textarea>
            </div>

            <div class="form-group">
                <label for="thumbnail">썸네일 이미지</label>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/*">
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">작성하기</button>
                <a href="news.php" class="cancel-btn">취소</a>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__left">
                    <img class="footer__logo" src="images/logo-white.png" alt="PLUXITY">
                    <div class="footer__info">
                        <p class="footer__address">서울특별시 강남구 테헤란로 14길 6 남도빌딩 2층</p>
                        <p class="footer__contact">Tel. 02-000-0000 | Fax. 02-000-0000 | Email. contact@pluxity.com</p>
                        <p class="footer__copyright">© PLUXITY. All Rights Reserved</p>
                    </div>
                    <ul>
                        <li><a href="#" title="개인정보처리방침">개인정보처리방침</a></li>
                        <li><a href="#">사이트맵</a></li>
                        <li><a href="#" title="이메일무단수집거부">이메일무단수집거부</a></li>
                        <li><a href="#">온라인문의</a></li>
                    </ul>
                </div>
                <div class="footer__right">
                    <div class="footer__cta">
                        <p class="footer__text">플럭시티의<br><span>디지털 트윈 플랫폼</span>을 만나보세요.</p>
                        <span class="footer__more">문의하기</span>
                    </div>
                    <div class="footer__right-content">
                        <div class="footer__brands">
                            <span>brand</span>
                            <div>
                                <img src="images/golf-logo-small.png" alt="Golf">
                                <img src="images/safers-logo-small.png" alt="Safers">
                            </div>
                        </div>
                        <div class="footer__social">
                            <span>Social Media</span>
                            <div>
                                <img src="images/youtube-icon.png" alt="YouTube">
                                <img src="images/linkedin-icon.png" alt="LinkedIn">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Privacy Policy Popup -->
    <div id="privacyPopup" class="popup">
        <div class="popup-content">
            <div class="popup-header">
                <div class="header-content">
                    <h3>개인정보 수집 및 이용에 대한 동의 안내</h3>
                    <p class="popup-desc">개인정보 수집, 이용 처리에 관한 사항을 안내드리겠습니다.</p>
                </div>
                <button class="popup-close">×</button>
            </div>
            <div class="popup-body">
                <div class="privacy-section">
                    <p>1. 개인정보 수집 및 이용목적</p>
                    <ul>
                        <li>• 개인식별 및 본인여부 확인/고객문의에 대한 상담 및 정보제공 처리</li>
                        <li>• 신규서비스 소개 및 고객별 맞춤 웹서비스 안내 등 마케팅 활용</li>
                    </ul>

                    <p>2. 수집하는 개인정보의 항목</p>
                    <ul>
                        <li>• 필수: 이름, 연락처</li>
                    </ul>

                    <p>3. 개인정보의 보유 및 이용기간</p>
                    <ul>
                        <li>• 문의완료일로부터 3년간 보관후 삭제</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Collection Popup -->
    <div id="emailPopup" class="popup">
        <div class="popup-content">
            <div class="popup-header">
                <div class="header-content">
                    <h3>이메일무단수집거부</h3>
                    <p class="popup-desc">이메일 무단수집을 거부합니다.</p>
                </div>
                <button class="popup-close">×</button>
            </div>
            <div class="popup-body">
                <div class="privacy-section">
                    <p>1. 개인정보 수집 및 이용목적</p>
                    <ul>
                        <li>• 개인식별 및 본인여부 확인/고객문의에 대한 상담 및 정보제공 처리</li>
                        <li>• 신규서비스 소개 및 고객별 맞춤 웹서비스 안내 등 마케팅 활용</li>
                    </ul>

                    <p>2. 수집하는 개인정보의 항목</p>
                    <ul>
                        <li>• 필수: 이메일, 연락처</li>
                    </ul>

                    <p>3. 개인정보의 보유 및 이용기간</p>
                    <ul>
                        <li>• 문의완료일로부터 3년간 보관후 삭제</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Popup functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Privacy Policy Popup
            const privacyLink = document.querySelector('a[href="#"][title="개인정보처리방침"]');
            const privacyPopup = document.getElementById('privacyPopup');
            const privacyCloseBtn = privacyPopup.querySelector('.popup-close');

            // Email Collection Popup
            const emailLink = document.querySelector('a[href="#"][title="이메일무단수집거부"]');
            const emailPopup = document.getElementById('emailPopup');
            const emailCloseBtn = emailPopup.querySelector('.popup-close');

            function showPopup(popup) {
                popup.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function hidePopup(popup) {
                popup.classList.remove('show');
                document.body.style.overflow = '';
            }

            // Privacy Policy Event Listeners
            privacyLink.addEventListener('click', function(e) {
                e.preventDefault();
                showPopup(privacyPopup);
            });

            privacyCloseBtn.addEventListener('click', function() {
                hidePopup(privacyPopup);
            });

            privacyPopup.addEventListener('click', function(e) {
                if (e.target === privacyPopup) {
                    hidePopup(privacyPopup);
                }
            });

            // Email Collection Event Listeners
            emailLink.addEventListener('click', function(e) {
                e.preventDefault();
                showPopup(emailPopup);
            });

            emailCloseBtn.addEventListener('click', function() {
                hidePopup(emailPopup);
            });

            emailPopup.addEventListener('click', function(e) {
                if (e.target === emailPopup) {
                    hidePopup(emailPopup);
                }
            });
        });
    </script>
</body>
</html> 