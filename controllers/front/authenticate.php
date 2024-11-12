<?php

use PrestaShop\PrestaShop\Adapter\Entity\Employee;

class AuthenticationApiAuthenticateModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        header('Content-Type: application/json');

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
            error_log(print_r($employee, true));
            http_response_code(401);
            echo json_encode(['error' => 'Credenciales inválidas']);
            return;
        }

        // Generar token y guardarlo
        $token = bin2hex(random_bytes(32));
        $expiryDate = date('Y-m-d H:i:s', strtotime('+1 hour'));

        Db::getInstance()->insert('authentication_token', [
            'id_employee' => $employee->id,
            'token' => $token,
            'expiry_date' => $expiryDate,
        ]);

        echo json_encode(['token' => $token, 'expiry_date' => $expiryDate,]);
    }
}
