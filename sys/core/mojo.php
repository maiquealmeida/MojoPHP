<?php

if ( ! defined('BASE_PATH')) exit('Acesso negado!');

/**
 * Defini a versão do Framework.
 */
define('VERSAO', '0.01');

/**
 * A classe App() possui funcionalidades importantes no funcionamento do Mojo*PHP
 * confirmando caminhos e importando os arquivos da aplicação.
 * 
 * @packageMojo*PHP
 * @author Eliel de Paula <elieldepaula@gmail.com>
 * @since 26/12/2012
 */
class App {

    /**
     * Importa um arquivo para ficar disponível para o sistema.
     * 
     * @param type $type
     * @param type $file
     * @param type $ext
     * @return type 
     */
    public static function import($type = "core", $file = "", $ext = "php") {

        if (is_array($file)):

            foreach ($file as $file):

                $include = self::import($type, $file, $ext);

            endforeach;

            return $include;

        else:

            if ($file_path = self::path($type, $file, $ext)):

                return require_once $file_path;

            else:

                trigger_error("File {$file}.{$ext} doesn't exists in {$type}", E_USER_WARNING);

            endif;

        endif;

        return false;
        
    }

    /**
     * Retorna o caminho completo de um arquivo solicitado durante a o import()
     * ou confirmando sua existencia quando necessário.
     * 
     * @param type $type
     * @param type $file
     * @param type $ext
     * @return string 
     */
    public static function path($type = "core", $file = "", $ext = "php") {
        $paths = array(
            "core" => array(CORE),
            "controller" => array(APP . DS . 'controllers', SYS . DS . 'library/controllers'),
            "model" => array(APP . DS . 'models'),
            "views" => array(APP . DS . 'views',  SYS . DS . 'library/views'),
            "helper" => array(APP . DS . 'helpers', SYS . DS . 'helpers'),
            "lib" => array(APP . DS . 'library', SYS . DS . 'library'),
            "config" => array(APP . DS . 'config', SYS . DS . 'config'), 
            "drivers" => array(SYS . DS . 'library' . DS . 'drivers')
        );

        foreach ($paths[$type] as $path):

            $file_path = $path . DS . "{$file}.{$ext}";

            if (file_exists($file_path)):

                return $file_path;

            endif;

        endforeach;

        return false;
    }

}

/**
 * A classe Config trata do gerenciamento das configurações do sistema.
 * 
 * @packageMojo*PHP
 * @author Eliel de Paula <elieldepaula@gmail.com>
 * @since 26/12/2012
 */
class Config {

    /**
     *  Definições de configurações.
     *
     *  @var array
     */
    private $config = array();

    /**
     *  Retorna uma única instância (Singleton) da classe solicitada.
     *
     *  @staticvar object $instance Objeto a ser verificado
     *  @return object Objeto da classe utilizada
     */
    public static function &getInstance() {
        static $instance = array();
        if (!isset($instance[0]) || !$instance[0]):
            $instance[0] = new Config();
        endif;
        return $instance[0];
    }

    /**
     *  Retorna o valor de uma determinada chave de configuração.
     *
     *  @param string $key Nome da chave da configuração
     *  @return mixed Valor de configuração da respectiva chave
     */
    public static function read($key = "") {
        $self = self::getInstance();
        return $self->config[$key];
    }

    /**
     *  Grava o valor de uma configuração da aplicação para determinada chave.
     *
     *  @param string $key Nome da chave da configuração
     *  @param string $value Valor da chave da configuração
     *  @return boolean true
     */
    public static function write($key = "", $value = "") {
        $self = self::getInstance();
        $self->config[$key] = $value;
        return true;
    }

}
