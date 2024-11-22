<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class AuthenticationApi extends Module
{
    public function __construct()
    {
        $this->name = 'authenticationapi';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Tu Nombre';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('API de Autenticación para Empleados');
        $this->description = $this->l('Proporciona un endpoint para autenticar empleados y devolver un token de sesión.');

        $this->confirmUninstall = $this->l('¿Estás seguro de que deseas desinstalar este módulo?');
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('moduleRoutes') &&
            $this->installDatabase();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDatabase();
    }

    private function installDatabase()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "authentication_token` (
            `id_token` INT(11) NOT NULL AUTO_INCREMENT,
            `id_employee` INT(11) NOT NULL,
            `token` VARCHAR(255) NOT NULL,
            `expiry_date` DATETIME NOT NULL,
            PRIMARY KEY (`id_token`)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

        return Db::getInstance()->execute($sql);
    }

    private function uninstallDatabase()
    {
        $sql = "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "authentication_token`;";
        return Db::getInstance()->execute($sql);
    }
}
