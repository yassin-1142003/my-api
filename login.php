<?php
header("Content-Type: application/json");

// اتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "your_database_name");

// التحقق من الاتصال
if ($conn->connect_error) {
    echo json_encode(["error" => "فشل الاتصال بقاعدة البيانات: " . $conn->connect_error]);
    exit;
}

// استقبال البيانات من `POST`
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["error" => "يرجى إدخال البريد الإلكتروني وكلمة المرور"]);
    exit;
}

$email = $conn->real_escape_string($data['email']);
$password = $data['password']; // نفترض أنها مشفرة في قاعدة البيانات

// البحث عن المستخدم في قاعدة البيانات
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // التحقق من كلمة المرور
    if (password_verify($password, $user['password'])) {
        echo json_encode(["success" => true, "message" => "تم تسجيل الدخول بنجاح", "user" => $user]);
    } else {
        echo json_encode(["error" => "كلمة المرور غير صحيحة"]);
    }
} else {
    echo json_encode(["error" => "المستخدم غير موجود"]);
}

$conn->close();
?>