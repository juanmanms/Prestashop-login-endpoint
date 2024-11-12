<?php

class AuthenticationToken extends ObjectModel
{
    public $id_token;
    public $id_employee;
    public $token;
    public $expiry_date;

    public static $definition = [
        'table' => 'authentication_token',
        'primary' => 'id_token',
        'fields' => [
            'id_employee' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'token' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255],
            'expiry_date' => ['type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => true],
        ],
    ];
}
