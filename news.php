<?php
// 오류 표시 설정
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

try {
    // 데이터베이스 연결 테스트
    $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    
    // 페이지네이션 설정
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = 5;
    $offset = ($page - 1) * $per_page;

    // 검색 기능
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    $where = [];
    $params = [];

    if (!empty($search)) {
        $where[] = "(title LIKE :search OR content LIKE :search)";
        $params[':search'] = "%$search%";
    }

    if (!empty($category)) {
        $where[] = "category = :category";
        $params[':category'] = $category;
    }

    $where_clause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    // 전체 게시글 수 조회
    $count_sql = "SELECT COUNT(*) FROM pluxity_news $where_clause";
    $stmt = $pdo->prepare($count_sql);
    $stmt->execute($params);
    $total_posts = $stmt->fetchColumn();
    $total_pages = ceil($total_posts / $per_page);

    // 게시글 목록 조회
    $sql = "SELECT * FROM pluxity_news $where_clause ORDER BY created_at DESC LIMIT :offset, :per_page";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 카테고리 목록 조회
    $categories = $pdo->query("SELECT DISTINCT category FROM pluxity_news ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    // 에러 로깅
    error_log("Database Error: " . $e->getMessage());
    $error_message = "데이터베이스 연결에 문제가 발생했습니다. 잠시 후 다시 시도해주세요.";
    $posts = [];
    $categories = [];
    $total_pages = 0;
    
    // 개발 환경에서만 상세 에러 표시
    if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') {
        $error_message .= "<br>상세 에러: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLUXITY - News & Notice</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/pluxity-board.css">
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

    <main class="news-page">
        <section class="news-hero">
            <h1>NEWS & NOTICE</h1>
            <p>플럭시티의<br>최신 소식들을 만나보세요.</p>
        </section>

        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <section class="news-list">
            <?php if (empty($posts)): ?>
                <div class="no-posts">
                    <p>등록된 게시글이 없습니다.</p>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                <article class="news-item">
                    <div class="news-thumbnail">
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?= htmlspecialchars($post['image']) ?>" alt="썸네일">
                        <?php else: ?>
                            <img src="./images/no-image.jpg" alt="기본 이미지">
                        <?php endif; ?>
                    </div>
                    <div class="news-content">
                        <span class="category"><?= htmlspecialchars($post['category']) ?></span>
                        <h3 class="title"><?= htmlspecialchars($post['title']) ?></h3>
                        <p class="excerpt"><?= htmlspecialchars(mb_strimwidth($post['content'], 0, 80, '...')) ?></p>
                        <div class="news-meta">
                            <span class="date"><?= date('Y. m. d', strtotime($post['created_at'])) ?></span>
                        </div>
                    </div>
                    <a href="view.php?id=<?= $post['id'] ?>" class="more-btn">원문 바로가기</a>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <div class="write-btn-area">
            <a href="write.php" class="write-btn">글쓰기</a>
        </div>

        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="prev">&lt;</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>" class="next">&gt;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
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