<?php

if (!defined('BASE_PATH'))exit('Acesso negado!');

/**
 * A clase MJ_Loader() faz o carregamento de todas as requisições das classes
 * do Mojo e registra os objetos usando a classe MJ_Registry()
 * 
 * @packageMojo*PHP
 * @author Eliel de Paula <elieldepaula@gmail.com>
 * @since 26/12/2012
 */
class MJ_Loader {

    function __construct() {}

    /**
     * Carrega um model.
     * 
     * @param type $class
     * @param type $name
     * @return type 
     */
    public function model($class = '', $name = NULL) {

        // Se $class estiver em branco retorna falso.
        if ($class == '')
            return false;

        $Clasname = ucfirst(strtolower($class));
        $PublicVar = strtolower($class);

        // Instancia o objeto
        $MJ = & MJ_Instance();
        $MJ->$PublicVar = Register('model', $class, $name);
    }

    /**
     * Carrega um helper.
     * 
     * @param type $file
     * @return type 
     */
    public function helper($file = '') {
        // Se $file estiver em branco retorna falso.
        if ($file == '')
            return false;

        //Verifica se o arquivo existe em uma das pastas (core ou app) e importa.
        if (file_exists(App::path('helper', $file . 'Helper'))) {
            App::import('helper', $file . 'Helper');
        } else {
            return false;
        }
    }

    /**
     * Carrega uma biblioteca.
     * 
     * @param type $class
     * @param type $name
     * @return type 
     */
    public function library($class = NULL, $name = NULL) {
        // Se $class estiver vazio para por aqui.
        if ($class == NULL)
            return false;

        $Clasname = ucfirst(strtolower($class));
        $PublicVar = strtolower($class);

        // Instancia o objeto    
        $MJ = & MJ_Instance();
        $MJ->$PublicVar = Register('lib', $class, $name);
             
    }
    
    /**
     * Carrega um driver de banco de dados.
     * 
     * @param type $driver
     * @param type $name
     * @param type $param
     * @return type 
     */
    public function driver($driver = '', $name = NULL, $param = NULL) {

        // Se $class estiver em branco retorna falso.
        if ($driver == '')
            return false;

        $Clasname = ucfirst(strtolower($driver));
        $PublicVar = strtolower($driver);

        // Instancia o objeto
        $MJ = & MJ_Instance();
        $MJ->$PublicVar = Register('drivers', $driver, $name, $param);
    }

    /**
     * Este método carrega uma view ao controller. O primeiro parâmetro é obrigatório
     * e informa o nome da view, o segunto parâmetro opcional informa os dados
     * a serem enviados para a view em forma de array, e o terçeito parâmetro
     * informa se deve retornar a view ou retorna em uma string.
     * 
     * Mais informações no guia do usuário.
     * 
     * @param string $view - Nome da view.
     * @param array $data - Dados a serem enviados (opcional).
     * @param bool $string - Se retorna a view o uma string.
     * @return mixed 
     */
    public function view($view, $data = null, $string = false) {

        $file = App::path('views', $view);

        if (sizeof($data) > 0)
            extract($data, EXTR_SKIP);

        if (file_exists($file)):

            if ($string):

                /**
                 * Retorna o arquivo como uma string.
                 */
                ob_start();

                /**
                 * Inclui o arquivo da view.
                 */
                include($file);

                $content = ob_get_contents();
                ob_end_clean();

                return $content;

            else:

                /**
                 * Inclui o arquivo da view.
                 */
                include($file);

            endif;

        else:

            die("Não foi possível carregar o arquivo da view: " . $file);

            //TODO Tratar uma nova exceção.

            return false;

        endif;

        return true;
    }

}

/**
 * Esta classe representa os objetos, ela é herdada pelas classes de modelo
 * e de controle do Mojo*PHP, ela herda MJ_Loader() para poder chamar
 * o carregamento e registro usando $this->load .. durante a implementação
 * das classes da aplicação.
 * 
 * @packageMojo*PHP
 * @author Eliel de Paula <elieldepaula@gmail.com>
 * @since 26/12/2012
 */
class MJ_Object extends MJ_Loader {

    public $load;
    private static $instance;

    public function __construct() {

        parent::__construct();

        $this->load = $this;

        self::$instance = $this->load;
    }

    public static function getInstance() {

        return self::$instance;
    }

}

/**
 * Retorna uma instância de MJ_Object.
 * 
 * @return mixed 
 */
function MJ_Instance() {
    return MJ_Object::getInstance();
}