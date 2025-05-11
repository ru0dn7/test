<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: news.php');
    exit;
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM pluxity_news WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: news.php');
    exit;
}

// 삭제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // 썸네일 이미지 삭제
    if ($post['image'] && file_exists($post['image'])) {
        unlink($post['image']);
    }
    
    // 게시물 삭제
    $deleteStmt = $pdo->prepare("DELETE FROM pluxity_news WHERE id = :id");
    $deleteStmt->bindValue(':id', $id, PDO::PARAM_INT);
    $deleteStmt->execute();
    
    header('Location: news.php');
    exit;
}

// 이전/다음 게시물 조회
$prevStmt = $pdo->prepare("SELECT id, title FROM pluxity_news WHERE id < :id ORDER BY id DESC LIMIT 1");
$prevStmt->bindValue(':id', $id, PDO::PARAM_INT);
$prevStmt->execute();
$prevPost = $prevStmt->fetch(PDO::FETCH_ASSOC);

$nextStmt = $pdo->prepare("SELECT id, title FROM pluxity_news WHERE id > :id ORDER BY id ASC LIMIT 1");
$nextStmt->bindValue(':id', $id, PDO::PARAM_INT);
$nextStmt->execute();
$nextPost = $nextStmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - PLUXITY</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/pluxity-board.css">
    <script>
        function confirmDelete() {
            return confirm('정말로 이 게시물을 삭제하시겠습니까?');
        }
    </script>
</head>
<body>
    <header>
        <div class="header-container">
            <h1><a href="index.php"><span class="hidden">PLUXITY</span><img src="./images/logo.png" alt="PLUXITY"></a></h1>
            <nav>
                <h2 class="hidden">메인 네비게이션</h2>
                <ul>
                    <li><a href="./about.html">ABOUT</a></li>
                    <li><a href="./achievements.html">Achievements</a></li>
                    <li><a href="#">PLUG</a></li>
                    <li><a href="#">CAREER</a></li>
                    <li><a href="./news.php" class="active">NEWS</a></li>
                    <li><a href="./contact_us.php">CONTACT US</a></li>
                </ul>
            </nav>
            <ul class="lang">
                <li class="on"><a href="#">KR</a></li>
                <li><a href="#">EN</a></li>
            </ul>
        </div>
    </header>

    <main class="news-detail">
        <div class="news-detail-container">
            <div class="news-header">
                <h1>NEWS & NOTICE</h1>
                <p>플럭시티의<br>최신 소식들을 만나보세요.</p>
            </div>

            <article class="news-content">
                <h2 class="title"><?php echo htmlspecialchars($post['title']); ?></h2>
                <div class="news-meta">
                    <span class="category"><?php echo htmlspecialchars($post['category']); ?></span>
                    <span class="date"><?php echo date('Y. m. d', strtotime($post['created_at'])); ?></span>
                </div>
                
                <div class="news-body">
                    <?php if ($post['image']): ?>
                    <div class="thumbnail">
                        <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="뉴스 이미지">
                    </div>
                    <?php endif; ?>
                    <div class="content">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                    <div class="post-actions">
                        <a href="edit.php?id=<?php echo $post['id']; ?>" class="edit-btn">수정</a>
                        <form method="POST" onsubmit="return confirmDelete();" style="display: inline;">
                            <input type="hidden" name="delete" value="1">
                            <button type="submit" class="delete-btn">삭제</button>
                        </form>
                    </div>
                </div>
            </article>

            <div class="post-navigation">
                <?php if ($prevPost): ?>
                    <a href="view.php?id=<?php echo $prevPost['id']; ?>" class="nav-button prev">
                        <span class="text">< 이전글</span>
                    </a>
                <?php else: ?>
                    <span class="no-article-msg">이전글이 없습니다</span>
                <?php endif; ?>
                
                <a href="news.php" class="nav-button list">
                    <span class="text">목록</span>
                </a>
                
                <?php if ($nextPost): ?>
                    <a href="view.php?id=<?php echo $nextPost['id']; ?>" class="nav-button next">
                        <span class="text">다음글 ></span>
                    </a>
                <?php else: ?>
                    <span class="no-article-msg">다음글이 없습니다</span>
                <?php endif; ?>
            </div>
        </div>
    </main>

        <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__left">
                    <img class="footer__logo" src="./images/logo-white.png" alt="PLUXITY">
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


                    <!-- <div class="footer__right-content">
                        <p>Brand</p>
                        <div class="footer__brands-container">
                            <div class="footer__brands">
                                <img class="footer__brand-logo" src="./images/golf-logo-small.png" alt="Golf">
                                <img class="footer__brand-logo" src="./images/safers-logo-small.png" alt="Safers">
                            </div>
                            <div class="footer__social">
                                <a href="#" class="footer__social-link">
                                    <img src="./images/youtube-icon.png" alt="YouTube">
                                </a>
                                <a href="#" class="footer__social-link">
                                    <img src="./images/linkedin-icon.png" alt="LinkedIn">
                                </a>
                            </div>
                        </div>
                    </div> -->
                    <div class="footer__right-content">
                        <div class="footer__brands">
                            <span>brand</span>
                            <div>
                                <img src="./images/golf-logo-small.png" alt="Golf">
                                <img src="./images/safers-logo-small.png" alt="Safers">
                            </div>
                        </div>
                        <div class="footer__social">
                            <span>Social Media</span>
                            <div>
                                <img src="./images/youtube-icon.png" alt="YouTube">
                                <img src="./images/linkedin-icon.png" alt="LinkedIn">
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