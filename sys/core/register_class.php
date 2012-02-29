<?php

if ( ! defined('BASE_PATH')) exit('Acesso negado!');

/**
 * A classe abstrata Registry é herdada pela classe Registro para trabalhar
 * com o registro de objetos no sistema.
 * 
 * @packageMojo*PHP
 * @author Eliel de Paula <elieldepaula@gmail.com>
 * @since 26/12/2012
 */
abstract class MJ_Registry {
    
    abstract protected function get($key);

    abstract protected function set($key, $val);
    
}

class Object_Registry extends MJ_Registry {

    /**
     * Registry array of objects 
     * @access private 
     */
    private static $objects = array();

    /**
     * The instance of the registry 
     * @access private 
     */
    private static $instance;

    //prevent directly access.
    private function __construct() {
        
    }

    //prevent clone. 
    public function __clone() {
        
    }

    /**
     * singleton method used to access the object 
     * @access public 
     */
    public static function singleton() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            //echo "new instance";
        } else {
            //echo "old instance";
        }
        return self::$instance;
    }

    protected function get($key) {
        if (isset($this->objects[$key])) {
            return $this->objects[$key];
        }
        return NULL;
    }

    protected function set($key, $val) {
        $this->objects[$key] = $val;
    }

    //static get request handle
    static function getObject($key) {

        return self::singleton()->get($key);
    }

    //store object
    static function storeObject($key, $instance) {

        return self::singleton()->set($key, $instance);
    }

}


function Register($tipo, $class, $name = NULL, $param = NULL) {
    
    $Obj = Object_Registry::singleton();

    $Class = $class;
    
    // Se o objeto já está registrado encerra aqui.
    if ($Obj->getObject($Class) !== NULL) {
        
        return $Obj->getObject($Class);
        
    }

    if (file_exists(App::path($tipo, ucfirst($Class)))) {
        
        App::import($tipo, ucfirst($Class));
        
    } else {
        
        echo 'Erro no registro da biblioteca ' . $class;
        
    }

    if($name == NULL):
        $classname = ucfirst($class);
    else:
        $classname = $name;
    endif;
    
    $Obj->storeObject($Class, new $classname($param));

    //Retorna o objeto singleton
    $Object = $Obj->getObject($Class);

    if (is_object($Object))
        return $Object;
}