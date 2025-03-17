<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json; charset=UTF-8");

require_once "config/db.php";

$db = new Db();
$conn = $db->getConnection();

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "فشل في الاتصال بقاعدة البيانات"]);
    exit;
}

$response = ["status" => "error", "message" => "حدث خطأ أثناء تنفيذ الطلب."];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_POST["full_name"], $_POST["national_id"], $_POST["email"], $_POST["password"]) &&
        isset($_FILES["profile_image"])
    ) {
        // تنظيف المدخلات باستخدام PDO
        $full_name = htmlspecialchars($_POST["full_name"]);
        $national_id = htmlspecialchars($_POST["national_id"]);
        $email = htmlspecialchars($_POST["email"]);
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        // رفع الصورة
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $image_path = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $image_path)) {
            $stmt = $conn->prepare("INSERT INTO users (full_name, national_id, email, password, profile_image) VALUES (?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->execute([$full_name, $national_id, $email, $password, $image_path]);
                $response = ["status" => "success", "message" => "تم التسجيل بنجاح"];
            } else {
                $response = ["status" => "error", "message" => "فشل في تحضير الاستعلام."];
            }
        } else {
            $response = ["status" => "error", "message" => "فشل في رفع الصورة."];
        }
    } else {
        $response = ["status" => "error", "message" => "يرجى ملء جميع الحقول المطلوبة."];
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>