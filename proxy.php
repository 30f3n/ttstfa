<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $phone = $input['message'] ?? '';

    if (empty($phone)) {
        echo json_encode(["error" => "الرقم مطلوب"]);
        exit;
    }

    $url = 'https://sin-osint.wasmer.app/api/ai/chat';
    
    $data = json_encode(['message' => $phone]);

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n" .
                         "Accept: application/json, text/plain, */*\r\n" .
                         "Cookie: sin_user_id=0e3d6d8838c7f7143fdbc5b7876a2792159918450458f3bc\r\n" .
                         "Origin: https://sin-osint.wasmer.app\r\n" .
                         "Referer: https://sin-osint.wasmer.app/\r\n" .
                         "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
            'method'  => 'POST',
            'content' => $data,
            'ignore_errors' => true
        ]
    ];

    $context  = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo json_encode(["error" => "فشل الاتصال بالخادم المستهدف"]);
    } else {
        echo $result;
    }
}
else {
    echo json_encode(["error" => "طريقة طلب غير صالحة"]);
}
?>