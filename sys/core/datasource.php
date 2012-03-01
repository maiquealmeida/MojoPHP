<?php

if ( ! defined('BASE_PATH')) exit('Acesso negado!');

/**
 * Esta classe cuida da conexão entre a camada de dados e os models no Mojo*PHP
 * selecionando o drive correto e padronizando alguns comandos para os models.
 *
 * @packageMojo*PHP
 * @author Eliel de Paula <elieldepaula@gmail.com>
 * @since 26/12/2012
 */
class MJ_Datasource extends MJ_Object {
    
    public $db = NULL;
    
    function __construct() {
        parent::__construct();
       
        
        if(!defined(ENVIRONMENT)) {
            throw new Exception('ENVIRONMENT is not set. See your .htaccess file.');
            
        }
        
        $config = Config::read('database');
        $driver = $config[ENVIRONMENT]['driver'];
        $this->load->driver($driver, NULL, $config);
        $this->db = $this->$driver->getInstance(Config::read('database'));
        
    }
    
    
    
}
