<?php
// 에러 로깅 활성화
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 디버그 로그 파일 설정
ini_set('log_errors', 1);
ini_set('error_log', 'debug.log');

require_once 'config/database.php';

$errors = [];
$success = false;

// POST 요청 확인 및 로깅
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST 데이터 로깅
    error_log('Form submitted at: ' . date('Y-m-d H:i:s'));
    error_log('POST data: ' . print_r($_POST, true));

    // 필수 필드 검증
    $required_fields = ['name', 'email', 'phone', 'category', 'subject', 'content'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = '필수 항목을 모두 입력해주세요.';
            break;
        }
    }

    // 이메일 형식 검증
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = '올바른 이메일 주소를 입력해주세요.';
    }

    // 개인정보 동의 확인
    if (!isset($_POST['privacy_agree']) || $_POST['privacy_agree'] !== '1') {
        $errors[] = '개인정보 수집 및 이용에 동의해주세요.';
    }

    // 에러가 없으면 데이터베이스에 저장
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO inquiries (
                    name, email, phone, company, category, subject, content,
                    privacy_agree, marketing_agree, status, created_at
                ) VALUES (
                    :name, :email, :phone, :company, :category, :subject, :content,
                    :privacy_agree, :marketing_agree, 'pending', NOW()
                )
            ");

            $stmt->execute([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'company' => $_POST['company'] ?? '',
                'category' => $_POST['category'],
                'subject' => $_POST['subject'],
                'content' => $_POST['content'],
                'privacy_agree' => $_POST['privacy_agree'] === '1' ? 1 : 0,
                'marketing_agree' => isset($_POST['marketing_agree']) && $_POST['marketing_agree'] === '1' ? 1 : 0
            ]);

            $inquiry_id = $pdo->lastInsertId();

            // 관리자에게 이메일 알림 발송
            $to = "kkwoou@naver.com";
            $subject = "[PLUXITY] 새로운 문의가 접수되었습니다.";
            $message = "새로운 문의가 접수되었습니다.\n\n";
            $message .= "문의번호: " . $inquiry_id . "\n";
            $message .= "이름: " . $_POST['name'] . "\n";
            $message .= "이메일: " . $_POST['email'] . "\n";
            $message .= "연락처: " . $_POST['phone'] . "\n";
            $message .= "회사명: " . ($_POST['company'] ?? '없음') . "\n";
            $message .= "문의유형: " . $_POST['category'] . "\n";
            $message .= "제목: " . $_POST['subject'] . "\n";
            $message .= "내용: " . $_POST['content'] . "\n";

            $headers = "From: " . $_POST['email'] . "\r\n";
            $headers .= "Reply-To: " . $_POST['email'] . "\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            mail($to, $subject, $message, $headers);

            $success = true;
            
            // 폼 초기화
            $_POST = array();
        } catch (Exception $e) {
            error_log('Error in form processing: ' . $e->getMessage());
            $errors[] = '문의 접수 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLUXITY - Contact Us</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/contact_us.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1><a href="./index.php"><span class="hidden">PLUXITY</span><img src="./images/logo.png" alt="PLUXITY"></a></h1>
            <nav>
                <h2 class="hidden">메인 네비게이션</h2>
                <ul>
                    <li><a href="./about.html">ABOUT</a></li>
                    <li><a href="./achievements.html">Achievements</a></li>
                    <li><a href="#">PLUG</a></li>
                    <li><a href="#">CAREER</a></li>
                    <li><a href="./news.php">NEWS</a></li>
                    <li><a class="active" href="./contact_us.php">CONTACT US</a></li>
                </ul>
            </nav>
            <ul class="lang">
                <li class="on"><a href="#">KR</a></li>
                <li><a href="#">EN</a></li>
            </ul>
        </div>
    </header>

    <main id="contact" class="page-content">
        <div class="hero-section">
            <div class="container">
                <h2 class="main-title">INQUIRY</h2>
                <p class="subtitle">
                    플럭시티의 디지털 트윈 플랫폼을 통해<br>
                    <b>새롭고 혁신적인 경험을 만나보세요.</b>
                </p>
                <br>
                <p class="description">
                    문의하기로 남겨주시면, 각 담당자 확인 후 신속히 연락드리겠습니다.
                </p>
            </div>
        </div>

        <div class="form-section container anchor-wrap">
            <i class="anchor" id="anc1"></i>
            <div class="form-container">
                <h2 class="section-title">문의하기</h2>
                
                <?php if (!empty($errors)): ?>
                <div class="error-messages" style="color: red; margin-bottom: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($success): ?>
                <script>
                    alert('문의가 성공적으로 접수되었습니다. 빠른 시일 내에 답변 드리겠습니다.');
                </script>
                <?php endif; ?>

                <div class="category-tabs">
                    <a href="#" class="tab-item active">디지털 트윈 플랫폼 <small>PLUG</small></a>
                    <a href="#" class="tab-item">스마트 건설안전 플랫폼 <small>Safers</small></a>
                    <a href="#" class="tab-item">스마트 골프 플랫폼 <small>Golf</small></a>
                    <a href="#" class="tab-item">마케팅 / 대외협력</a>
                    <a href="#" class="tab-item">채용</a>
                    <a href="#" class="tab-item">기타</a>
                </div>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="contact-form">
                    <input type="hidden" name="category" id="category" value="dt">
                    
                    <table class="form-table">
                        <colgroup>
                            <col class="col--th">
                            <col class="col--td">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th><div class="label">성함 <span class="required">*</span></div></th>
                                <td>
                                    <div class="input-group">
                                        <div class="input-box">
                                            <input type="text" name="name" id="name" placeholder="예) 김플럭" class="input required" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><div class="label">이메일 <span class="required">*</span></div></th>
                                <td>
                                    <div class="input-group">
                                        <div class="input-box">
                                            <input type="email" name="email" id="email" placeholder="예) example@pluxity.com" class="input email required" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><div class="label">연락처 <span class="required">*</span></div></th>
                                <td>
                                    <div class="input-group">
                                        <div class="input-box">
                                            <input type="tel" name="phone" id="contact" placeholder="- 없이 숫자만 입력" class="input number required" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><div class="label">회사명</div></th>
                                <td>
                                    <div class="input-group">
                                        <div class="input-box">
                                            <input type="text" name="company" id="company" placeholder="현재 소속된 단체명 또는 개인으로 표기" class="input" value="<?php echo isset($_POST['company']) ? htmlspecialchars($_POST['company']) : ''; ?>">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><div class="label">문의유형 <span class="required">*</span></div></th>
                                <td>
                                    <div class="input-group">
                                        <select name="category" id="category" class="input" required>
                                            <option value="">선택해주세요</option>
                                            <option value="general" <?php echo (isset($_POST['category']) && $_POST['category'] === 'general') ? 'selected' : ''; ?>>일반문의</option>
                                            <option value="business" <?php echo (isset($_POST['category']) && $_POST['category'] === 'business') ? 'selected' : ''; ?>>비즈니스 제안</option>
                                            <option value="technical" <?php echo (isset($_POST['category']) && $_POST['category'] === 'technical') ? 'selected' : ''; ?>>기술지원</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><div class="label">제목 <span class="required">*</span></div></th>
                                <td>
                                    <div class="input-group">
                                        <div class="input-box">
                                            <input type="text" name="subject" id="subject" placeholder="제목을 입력해주세요." class="input required" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" required>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><div class="label">내용 <span class="required">*</span></div></th>
                                <td>
                                    <div class="input-group">
                                        <div class="input-box">
                                            <textarea name="content" id="content" class="required" placeholder="문의 내용을 입력해주세요." required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <td>
                                    <div class="form-footer">
                                        <div class="checkbox-group">
                                            <div class="input-group checkbox">
                                                <label for="privacy-agree">
                                                    <input type="checkbox" id="privacy-agree" name="privacy_agree" value="1" data-check-group="policy" <?php echo (isset($_POST['privacy_agree']) && $_POST['privacy_agree'] === '1') ? 'checked' : ''; ?>>
                                                    <span class="checkbox-custom"></span>
                                                    <span class="label"><i class="link modal-trigger" data-modal-id="privacy-inquiry">개인정보처리 방침</i>에 동의합니다. (필수)</span>
                                                </label>
                                            </div>
                                            <div class="input-group checkbox">
                                                <label for="mkt-agree">
                                                    <input type="checkbox" id="mkt-agree" name="marketing_agree" value="1" data-check-group="policy" <?php echo (isset($_POST['marketing_agree']) && $_POST['marketing_agree'] === '1') ? 'checked' : ''; ?>>
                                                    <span class="checkbox-custom"></span>
                                                    <span class="label">플럭시티 관련 <i class="link modal-trigger" data-modal-id="mkt-inquiry">프로모션 및 마케팅 정보 수신</i>에 동의합니다. (선택)</span>
                                                </label>
                                            </div>
                                        </div>
                                        <br class="t-br"><br class="m-br">
                                        <div class="button-group">
                                            <button type="submit" class="submit-button"><span>문의하기</span></button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
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
    document.addEventListener('DOMContentLoaded', function() {
        // 카테고리 탭 처리
        const categoryTabs = document.querySelectorAll('.tab-item');
        const categoryInput = document.getElementById('category');
        
        categoryTabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                categoryTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const categoryValue = this.querySelector('small')?.textContent.toLowerCase() || this.textContent.trim().toLowerCase();
                categoryInput.value = categoryValue;
            });
        });

        // 팝업 관련 코드는 그대로 유지
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