<?php

if ( ! defined('BASE_PATH')) exit('Acesso negado!');

/**
 * Este script cuida da inicialização do Mojo*PHP, ele faz o carregamento de
 * todos os arquivos necessários e inicia o roteamento dos controladores.
 * 
 * @package Mojo*PHP
 * @author Eliel de Paula <elieldepaula@gmail.com>
 * @since 26/02/2012
 * 
 */

require_once CORE . DS . 'mojo.php';

App::import('config', array('common', 'constants', 'database'));
App::import('core', array('register_class', 'loader', 'datasource', 'controller', 'model', 'router'));

MJ_Router::getInstance();
