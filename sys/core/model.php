<?php

if ( ! defined('BASE_PATH')) exit('Acesso negado!');

/**
 * Classe base para os models da aplicação.
 *
 * @packageMojo*PHP
 * @author Eliel de Paula <elieldepaula@gmail.com>
 * @since 26/12/2012
 */
class MJ_Model extends MJ_Datasource {
    
    function __construct() {
        parent::__construct();
    }
    
}
