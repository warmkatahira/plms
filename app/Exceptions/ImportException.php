<?php

namespace App\Exceptions;

use Exception;

class ImportException extends Exception
{
    protected $import_type;
    protected $error_file_name;
    protected $import_original_file_name;

    public function __construct($message, $import_type = null, $error_file_name = null, $import_original_file_name = null, $code = 0, Exception $previous = null)
    {
        $this->message = $message;
        $this->import_type = $import_type;
        $this->error_file_name = $error_file_name;
        $this->import_original_file_name = $import_original_file_name;
    }

    public function getImportType()
    {
        return $this->import_type;
    }

    public function getErrorFileName()
    {
        return $this->error_file_name;
    }

    public function getImportOriginalFileName()
    {
        return $this->import_original_file_name;
    }
}