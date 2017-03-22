<?php

class GetFile
{
    public $upload_dir;

    function __construct(){
        $this->upload_dir = wp_upload_dir();
    }

    function open_file_to_read($file){

        $file = $this->upload_dir['basedir'] . '/' . $file;
        return $handel_file = fopen($file, 'rt');
    }
}