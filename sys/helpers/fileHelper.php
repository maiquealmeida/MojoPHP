<?php

/**
 * Documentar este helper...
 * 
 * @package Mojo*PHP
 * @since 26/02/2012 
 */

function delete_files($path, $del_dir = FALSE, $level = 0) {
    
    $path = rtrim($path, '/');

    if (!$current_dir = @opendir($path)) {
        return FALSE;
    }

    while (FALSE !== ($filename = @readdir($current_dir))) {
        if ($filename != "." and $filename != "..") {
            if (is_dir($path . '/' . $filename)) {
                // Ignore empty folders
                if (substr($filename, 0, 1) != '.') {
                    delete_files($path . '/' . $filename, $del_dir, $level + 1);
                }
            } else {
                unlink($path . '/' . $filename);
            }
        }
    }
    @closedir($current_dir);

    if ($del_dir == TRUE AND $level > 0) {
        return @rmdir($path);
    }

    return TRUE;
}

function get_filenames($source_dir, $include_path = FALSE, $_recursion = FALSE) {
    static $_filedata = array();

    if ($fp = @opendir($source_dir)) {
        // reset the array and make sure $source_dir has a trailing slash on the initial call
        if ($_recursion === FALSE) {
            $_filedata = array();
            $source_dir = rtrim(realpath($source_dir), '/') . '/';
        }

        while (FALSE !== ($file = readdir($fp))) {
            if (@is_dir($source_dir . $file) && strncmp($file, '.', 1) !== 0) {
                get_filenames($source_dir . $file . '/', $include_path, TRUE);
            } elseif (strncmp($file, '.', 1) !== 0) {
                $_filedata[] = ($include_path == TRUE) ? $source_dir . $file : $file;
            }
        }
        return $_filedata;
    } else {
        return FALSE;
    }
}
