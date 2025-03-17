<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// تأكد من أن ملف الاتصال بقاعدة البيانات موجود والمسار صحيح
// إذا كان verify_email.php موجود في htdocs مباشرة و db.php داخل مجلد config داخل htdocs، فالمسار يكون:
require_once __DIR__ . '/config/db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // البحث عن المستخدم الذي يحمل هذا التوكن
    $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = ? LIMIT 1");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // التحقق مما إذا كان البريد الإلكتروني مفعل بالفعل
        if ($user['verified'] == 1) {
            echo "البريد الإلكتروني مفعل مسبقًا.";
        } else {
            // تحديث حالة التفعيل وإزالة التوكن
            $updateStmt = $pdo->prepare("UPDATE users SET verified = 1, verification_token = NULL WHERE id = ?");
            if ($updateStmt->execute([$user['id']])) {
                echo "تم تفعيل البريد الإلكتروني بنجاح!";
            } else {
                echo "حدث خطأ أثناء تفعيل البريد الإلكتروني، يرجى المحاولة مرة أخرى.";
            }
        }
    } else {
        echo "رمز التحقق غير صالح.";
    }
} else {
    echo "لم يتم توفير رمز التحقق.";
}
?>