<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLUXITY</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/common.css">
</head>

<body>
    <header>
        <div class="header-container">
            <h1><a href="#"><span class="hidden">PLUXITY</span><img src="./images/logo.png" alt="PLUXITY"></a></h1>
            <nav>
                <h2 class="hidden">메인 네비게이션</h2>
                <ul>
                    <li><a href="./about.html">ABOUT</a></li>
                    <li><a href="./achievements.html">Achievements</a></li>
                    <li><a href="#">PLUG</a></li>
                    <li><a href="#">CAREER</a></li>
                    <li><a href="./news.php">NEWS</a></li>
                    <li><a href="./contact_us.php">CONTACT US</a></li>
                </ul>
            </nav>
            <ul class="lang">
                <li class="on"><a href="#">KR</a></li>
                <li><a href="#">EN</a></li>
            </ul>
        </div>
    </header>
    <!-- Main Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>
                <span>DIGITAL TWIN</span>
                <span>PLATFORM</span>
            </h2>
            <p>플럭시티는 시각화된 3D공간과 기존 시스템의 결합으로<br>
새로운 관리 시스템을 제시합니다.</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="about">
        <div class="about-container">
            <div class="about-text fixed">
                <h3>About us</h3>
                <h2>공간의 전 생애주기에 부합하는<br><span>디지털 트윈 플랫폼을 제시합니다.</span></h2>
                <p>우리는 30년 이상의 축적 기술에서 디지털 트윈 산업 기술 개발으로 나아가고 있습니다.<br>
                    우리 미래의 사회에서 디지털 트윈을 구축하고 자원의 가치있는 관리와 함께 지속 가능한 가치를 보유하고 있습니다.</p>
                <button class="learn-more">자세히 알아보기 →</button>

            </div>
            <div class="cards-container">
                <div class="service-cards">
                    <div class="card">
                        <img src="./images/icon1.png" alt="실시간 모니터링">
                        <h4>실시간 모니터링</h4>
                        <p>·비대면 원격제어</p>
                        <p class="eng-text">Real-time remote<br>monitoring and control</p>
                    </div>
                    <div class="card">
                        <img src="./images/icon3.png" alt="재난·안전">
                        <h4>재난·안전</h4>
                        <p>·즉각 대응 및 예방</p>
                        <p class="eng-text">Prompt and effective<br>emergency management</p>
                    </div>
                    <div class="card">
                        <img src="./images/icon2.png" alt="통합 운영예측">
                        <h4>통합 운영예측</h4>
                        <p>·최적화</p>
                        <p class="eng-text">Integrated operation, prediction,<br>optimization</p>
                    </div>
                    <div class="card">
                        <img src="./images/icon4.png" alt="데이터 분석">
                        <h4>데이터 분석</h4>
                        <p>·AI 기반 분석</p>
                        <p class="eng-text">AI-based data<br>analysis and prediction</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plug Section -->
    <section class="plug">
        <div class="container">
            <div>
                <p>디지털 트윈 통합 플랫폼,</p>
                <h2>PLUG</h2>
            </div>
            <p>
                <span>PLUG는 우리의 모든 공간을</span>
                <strong>살아있는 가상공간으로</strong>
                <span>재탄생 시킵니다.</span>
        </div>
    </section>

    <!-- Smart Industry Section -->
    <section class="smart-industry">
        <div class="container">
            <h2>Smart Industry</h2>
            <p>스마트한 산업 현장을 만들어갑니다</p>
            <button class="learn-more">자세히 보기</button>
        </div>
    </section>

    <!-- Core Technology Section -->
    <section class="core-tech">
        <div class="container center">
            <h3>Core Technology</h3>
            <h2>과거, 현실, 미래를 담아내는 <span>우리의 기술</span></h2>
            <div class="tech-cards">
                <div class="tech-card">
                    <div class="tech-card-inner">
                        <img src="./images/tech-icon1.png" alt="Operational Optimization">
                        <h4>Operational Optimization</h4>
                        <p>산업별 최적화된 운영 솔루션을 제시합니다.</p>
                        <button class="learn-more">자세히 보기</button>
                    </div>
                </div>
                <div class="tech-card">
                    <div class="tech-card-inner">
                        <img src="./images/tech-icon2.png" alt="AI Simulation">
                        <h4>AI Simulation</h4>
                        <p>과거와 현실의 공간을 분석하여 미래를 실험하고, 예측합니다.</p>
                        <button class="learn-more">자세히 보기</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <?php
    require_once 'config.php';
    
    // 최신 3개 게시물 조회
    $stmt = $pdo->prepare("SELECT * FROM pluxity_news ORDER BY created_at DESC LIMIT 3");
    $stmt->execute();
    $latestPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <section class="news">
        <div class="container">
            <div class="section__left">
                <h3 class="section__subtitle">News & Notice</h3>
                <h2 class="section__title">플럭시티의<br>새로운 소식</h2>
            </div>
            <div class="section__right">
                <div class="news__list">
                    <?php foreach ($latestPosts as $post): ?>
                    <div class="news__item">
                        <div class="news__top">
                            <span class="news__category"><?php echo htmlspecialchars($post['category']); ?></span>
                        </div>
                        <div class="news__bottom">
                            <span class="news__date"><?php echo date('Y.m.d', strtotime($post['created_at'])); ?></span>
                            <p class="news__content"><?php echo htmlspecialchars($post['title']); ?></p>
                            <a href="view.php?id=<?php echo $post['id']; ?>" class="news__arrow">→</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <a href="news.php" class="btn-more">더보기</a>
            </div>
        </div>
    </section>

    <!-- Brand Section -->
    <section class="brand">
        <div class="container">
            <div class="section__left">
                <h3 class="section__subtitle">Brand</h3>
                <h2 class="section__title">스마트한<br>플럭시티 플랫폼</h2>
            </div>
            <div class="section__right">
                <div class="brand__cards">
                    <div class="brand__card">
                        <div class="brand__content">
                            <p class="brand__text">스마트 도시 운영 플랫폼</p>
                            <img class="brand__logo" src="./images/safers-logo.png" alt="Safers">
                        </div>
                        <span class="brand__arrow">바로가기 →</span>
                    </div>
                    <div class="brand__card">
                        <div class="brand__content">
                            <p class="brand__text">스마트 골프 플랫폼</p>
                            <img class="brand__logo" src="./images/golf-logo.png" alt="Golf">
                        </div>
                        <span class="brand__arrow">바로가기 →</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

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