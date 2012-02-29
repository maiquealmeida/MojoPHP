<?php

if ( ! defined('BASE_PATH')) exit('Acesso negado!');

/**
 * A classe MJ_Router trata do roteamento das requisições feitas no navegador
 * para os respectivos controllers da aplicação.
 * 
 * @packageMojo*PHP
 * @author Eliel de Paula <elieldepaula@gmail.com>
 * @since 26/12/2012
 */
class MJ_Router {
    
    private $path;
    public $file;
    public $controller;
    public $action;
    public $param;

    private function __construct() {
        
        $this->loader();
        
    }
    
    public static function &getInstance() {
        
        static $instance = array();
        
        if(!isset($instance[0]) || !$instance[0]):
            
            $instance[0] = new MJ_Router();
        
        endif;
        
        return $instance[0];
        
    }

    /**
     *
     * @load the controller
     *
     * @access public
     *
     * @return void
     *
     */
    public function loader() {
        
        /**
         * Verifica a rota.
         */
        $this->getController();

        /**
         * Se o controlador nao estiver disponível retorna o erro.
         */
        if (is_readable(APP . DS . 'controllers' . DS . $this->file . '.php') == false) {
            
            $this->file = $this->path . 'notfoundController';
            $this->controller = 'notfound';
            
        }

        /**
         * Importa o controlador.
         */
        App::import('controller', $this->file);

        /**
         * Inicia uma nova instancia do controlador.
         */
        $class = $this->controller . 'Controller';
        $controller = new $class();

        /*         * * check if the action is callable ** */
        if (is_callable(array($controller, $this->action)) == false) {
            $action = 'index';
        } else {
            $action = $this->action;
        }
        /*         * * run the action ** */
        $controller->$action($this->param);
    }

    /**
     *
     * @get the controller
     *
     * @access private
     *
     * @return void
     *
     */
    private function getController() {

        /**
         * Recupera a rota pela URL.
         */
        $route = (empty($_GET['rt'])) ? '' : $_GET['rt'];

        if(empty($route)):
            
            $route = 'index';
        
        else:
            
            /**
             * Pega a rota em partes.
             */
            $parts = explode('/', $route);
            $this->controller = $parts[0];
            
            if (isset($parts[1])):
                
                $this->action = $parts[1];
            
            endif;
            
            if (isset($parts[2])):
                
                $this->param = $parts[2];
            
            endif;
            
        endif;

        if(empty($this->controller)):
            
            $this->controller = 'index';
        
        endif;

        /**
         * Configura a ação padrão caso venha vazio pela rota.
         */
        if (empty($this->action)):
            
            $this->action = 'index';
        
        endif;

        /**
         * Configura o caminho para o arquivo do controlador.
         */
        $this->file = $this->controller . 'Controller';
        
    }

}