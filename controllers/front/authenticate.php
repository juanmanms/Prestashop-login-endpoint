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

function validateInput($input)
{
    if (!isset($input['email']) || !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        return 'Email inválido o faltante';
    }
    if (!isset($input['password']) || empty($input['password'])) {
        return 'Contraseña faltante';
    }
    return null;
}

function validateToken($token)
{
    $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'authentication_token WHERE token = "' . pSQL($token) . '" AND expiry_date > NOW()';
    $result = Db::getInstance()->getRow($sql);

    if (!$result) {
        return false;
    }
    return true;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $validationError = validateInput($input);

    if ($validationError) {
        http_response_code(400);
        echo json_encode(['error' => $validationError]);
        exit();
    }

    $email = $input['email'];
    $password = $input['password'];

    $employee = new Employee();
    if (!$employee->getByEmail($email, $password)) {
        http_response_code(401);
        echo json_encode(['error' => 'Credenciales inválidas']);
        exit();
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
    echo json_encode(['token' => $token, 'expiry_date' => $expiryDate, 'ProfileEmployed' => $employee->id_profile, 'id_employee' => $employee->id, 'name' => $employee->firstname]);
    exit();
} catch (Exception $e) {
    error_log($e->getMessage()); // Registrar el error en el log del servidor
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor', 'details' => $e->getMessage()]);
    exit();
}
