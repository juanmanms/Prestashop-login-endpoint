<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar la solicitud OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = isset($input['email']) ? $input['email'] : null;
    $password = isset($input['password']) ? $input['password'] : null;

    if (!$email || !$password) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan parámetros de autenticación']);
        return;
    }

    $employee = new Employee();
    if (!$employee->getByEmail($email, $password)) {
        http_response_code(401);
        echo json_encode(['error' => 'Credenciales inválidas']);
        return;
    }

    // Generar token y guardarlo
    $token = bin2hex(random_bytes(32));
    $expiryDate = date('Y-m-d H:i:s', strtotime('+12 hour'));

    Db::getInstance()->insert('authentication_token', [
        'id_employee' => $employee->id,
        'token' => $token,
        'expiry_date' => $expiryDate,
    ]);

    http_response_code(200);
    echo json_encode(['token' => $token, 'expiry_date' => $expiryDate, 'ProfileEmployed' => $employee->id_profile, 'id_employee' => $employee->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor', 'details' => $e->getMessage()]);
}
