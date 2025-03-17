<?php
// تضمين مكتبة PHPMailer والاتصال بقاعدة البيانات
require __DIR__ .'api_project/config/db.php'; // تأكد من أن هذا المسار صحيح
require __DIR__ . 'vendor/autoload.php';

// استيراد الفئات المطلوبة من PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // التحقق من وجود البريد الإلكتروني في قاعدة البيانات
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // إنشاء رمز إعادة تعيين كلمة المرور
        $token = bin2hex(random_bytes(50));

        // تحديث قاعدة البيانات برمز إعادة التعيين
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $stmt->execute([$token, $email]);

        // إرسال البريد الإلكتروني باستخدام PHPMailer
        $mail = new PHPMailer(true);

        try {
            // إعدادات SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com'; // ضع بريدك الإلكتروني
            $mail->Password = 'your-email-password'; // استخدم App Password إذا كنت على Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // إعداد البريد
            $mail->setFrom('your-email@gmail.com', 'Your App Name');
            $mail->addAddress($email);
            $mail->Subject = "Password Reset Request";
            $mail->isHTML(true);
            $mail->Body = "
                <h3>Password Reset Request</h3>
                <p>Click the link below to reset your password:</p>
                <a href='http://localhost/reset_password.php?token=$token'>Reset Password</a>
                <p>This link will expire in 1 hour.</p>
            ";

            // إرسال البريد
            $mail->send();
            echo "Password reset email has been sent!";
        } catch (Exception $e) {
            echo "Error sending email: " . $mail->ErrorInfo;
        }
    } else {
        echo "Email not found!";
    }
} else {
    echo "Invalid request!";
}
?>