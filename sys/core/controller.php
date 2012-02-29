<?php

if ( ! defined('BASE_PATH')) exit('Acesso negado!');

/**
 * Esta щ classe base para os controllers da aplicaчуo, ela herda a classe
 * MJ_Object que traz as opчѕes de de registro de objetos e recuperaчуo
 * das instтncias dos mesmos.
 * 
 * @package Mojo*PHP
 * @author Eliel de Paula <elieldepaula@gmail.com
 * @since 26/02/2012
 * 
 */

class MJ_Controller extends MJ_Object {
    
    function __construct(){
        parent::__construct();
    }
    
}