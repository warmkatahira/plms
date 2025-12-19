<?php

namespace App\Exceptions;

use Exception;

class OrderImportException extends Exception
{
    protected $import_info;
    protected $order_num;
    protected $error_file_name;

    public function __construct($message, $import_info = null, $order_num = null, $error_file_name = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->import_info = $import_info;
        $this->order_num = $order_num;
        $this->error_file_name = $error_file_name;
    }

    public function getImportInfo()
    {
        return $this->import_info;
    }

    public function getOrderNum()
    {
        return $this->order_num;
    }

    public function getErrorFileName()
    {
        return $this->error_file_name;
    }
}