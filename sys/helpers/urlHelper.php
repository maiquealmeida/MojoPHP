<?php

if ( ! defined('BASE_PATH')) exit('Acesso negado!');

/**
 * -----------------------------------------------------------------------------
 *                  HELPER URL
 * -----------------------------------------------------------------------------
 * 
 * Este arquivo é o helper de URL, ele tem que tratar a criação automazizada de 
 * links e de montar os parametros para os mesmos.
 * 
 * @packageMojo*PHP
 * @author Eliel de Paula <elieldepaula@gmail.com>
 * @since 26/12/2012
 * 
 */

/**
 * -------------------------------------------------------------------------
 *                  urlTitle()
 * -------------------------------------------------------------------------
 * 
 * Cria uma Url amigável
 *
 * Pega uma string de 'titulo' como entrada e cria uma url amigável 
 * separando as palavras com - (dash) ou _ (underscore)
 * 
 * Ex: Título do artigo -> Titulo-do-artigo
 * 
 * @access public
 * @param string $str - Título
 * @param string $separator - separador: dash ou underscore
 * @param boolean $lowercase - Deixa ou não o texto minúsculo
 * @return string
 *  
 */
function url_title($str, $separator = 'dash', $lowercase = FALSE) {

    if ($separator == 'dash') {
        $search = '_';
        $replace = '-';
    } else {
        $search = '-';
        $replace = '_';
    }

    $trans = array(
        '&\#\d+?;' => '',
        '&\S+?;' => '',
        '\s+' => $replace,
        '[^a-z0-9\-\._]' => '',
        $replace . '+' => $replace,
        $replace . '$' => $replace,
        '^' . $replace => $replace,
        '\.+$' => ''
    );

    $str = strip_tags($str);

    foreach ($trans as $key => $val) {
        $str = preg_replace("#" . $key . "#i", $val, $str);
    }

    if ($lowercase === TRUE) {
        $str = strtolower($str);
    }

    return trim(stripslashes($str));
}

/**
 * -------------------------------------------------------------------------
 *                  getRoute()
 * -------------------------------------------------------------------------
 * 
 * Este método retorna o valor passado pela URL de acordo com
 * o elemento informado.
 * 
 * Ex: get_route(4) retorna o quarto elemento da URL /1/2/3/4
 * 
 * @access public
 * @param int $pos - Posição na URL
 * @return string
 *  
 */
function get_route($pos) {

    $route = (empty($_GET['rt'])) ? '' : $_GET['rt'];

    $parts = explode('/', $route);

    return $parts[$pos];
}

function site_url($uri) {
    if (ENVIROMENT == 'desenvolvimento'):
        return BASE_URL . DS . 'index.php?rt=' . $uri;
    else:
        return BASE_URL . DS . $uri;
    endif;
}

/**
 * Cria um link
 * 
 * @access	public
 * @param	string	URL
 * @param	string	Titulo
 * @param	mixed	Atributos
 * @return	string
 */
function anchor($uri = '', $title = '', $attributes = '') {
    $title = (string) $title;

    if (!is_array($uri)) {
        $site_url = (!preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;
    } else {
        $site_url = site_url($uri);
    }

    if ($title == '') {
        $title = $site_url;
    }

    if ($attributes != '') {
        $attributes = _passa_atributos($attributes);
    }

    return '<a href="' . $site_url . '"' . $attributes . '>' . $title . '</a>';
}

/**
 * Link como pop-up.
 * 
 * @param type $uri
 * @param type $title
 * @param type $attributes
 * @return type 
 */
function anchor_popup($uri = '', $title = '', $attributes = FALSE) {
    $title = (string) $title;

    $site_url = (!preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;

    if ($title == '') {
        $title = $site_url;
    }

    if ($attributes === FALSE) {
        return "<a href='javascript:void(0);' onclick=\"window.open('" . $site_url . "', '_blank');\">" . $title . "</a>";
    }

    if (!is_array($attributes)) {
        $attributes = array();
    }

    foreach (array('width' => '800', 'height' => '600', 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0',) as $key => $val) {
        $atts[$key] = (!isset($attributes[$key])) ? $val : $attributes[$key];
        unset($attributes[$key]);
    }

    if ($attributes != '') {
        $attributes = _passa_atributos($attributes);
    }

    return "<a href='javascript:void(0);' onclick=\"window.open('" . $site_url . "', '_blank', '" . _passa_atributos($atts, TRUE) . "');\"$attributes>" . $title . "</a>";
}

/**
 * Gera um link Mailto:
 * 
 * @param type $email
 * @param type $title
 * @param type $attributes
 * @return type 
 */
function mailto($email, $title = '', $attributes = '') {
    $title = (string) $title;

    if ($title == "") {
        $title = $email;
    }

    $attributes = _passa_atributos($attributes);

    return '<a href="mailto:' . $email . '"' . $attributes . '>' . $title . '</a>';
}

/**
 * Cria um link mailto seguro com javascript.
 * 
 * @param type $email
 * @param type $title
 * @param type $attributes
 * @return type 
 */
function safe_mailto($email, $title = '', $attributes = '') {
    $title = (string) $title;

    if ($title == "") {
        $title = $email;
    }

    for ($i = 0; $i < 16; $i++) {
        $x[] = substr('<a href="mailto:', $i, 1);
    }

    for ($i = 0; $i < strlen($email); $i++) {
        $x[] = "|" . ord(substr($email, $i, 1));
    }

    $x[] = '"';

    if ($attributes != '') {
        if (is_array($attributes)) {
            foreach ($attributes as $key => $val) {
                $x[] = ' ' . $key . '="';
                for ($i = 0; $i < strlen($val); $i++) {
                    $x[] = "|" . ord(substr($val, $i, 1));
                }
                $x[] = '"';
            }
        } else {
            for ($i = 0; $i < strlen($attributes); $i++) {
                $x[] = substr($attributes, $i, 1);
            }
        }
    }

    $x[] = '>';

    $temp = array();
    for ($i = 0; $i < strlen($title); $i++) {
        $ordinal = ord($title[$i]);

        if ($ordinal < 128) {
            $x[] = "|" . $ordinal;
        } else {
            if (count($temp) == 0) {
                $count = ($ordinal < 224) ? 2 : 3;
            }

            $temp[] = $ordinal;
            if (count($temp) == $count) {
                $number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);
                $x[] = "|" . $number;
                $count = 1;
                $temp = array();
            }
        }
    }

    $x[] = '<';
    $x[] = '/';
    $x[] = 'a';
    $x[] = '>';

    $x = array_reverse($x);
    ob_start();
    ?><script type="text/javascript">
        //<![CDATA[
        var l=new Array();
    <?php
    $i = 0;
    foreach ($x as $val) {
        ?>l[<?php echo $i++; ?>]='<?php echo $val; ?>';<?php } ?>

            for (var i = l.length-1; i >= 0; i=i-1){
                if (l[i].substring(0, 1) == '|') document.write("&#"+unescape(l[i].substring(1))+";");
                else document.write(unescape(l[i]));}
            //]]>
    </script><?php
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}

function _passa_atributos($attributes, $javascript = FALSE) {
    if (is_string($attributes)) {
        return ($attributes != '') ? ' ' . $attributes : '';
    }

    $att = '';
    foreach ($attributes as $key => $val) {
        if ($javascript == TRUE) {
            $att .= $key . '=' . $val . ',';
        } else {
            $att .= ' ' . $key . '="' . $val . '"';
        }
    }

    if ($javascript == TRUE AND $att != '') {
        $att = substr($att, 0, -1);
    }

    return $att;
}

function redirect($uri){
    print '<script>document.location="' . site_url($uri) . '";</script>';
}