<?php
set_exception_handler(['AppExceptionHandler','AppExceptionHandle']);
class AppExceptionHandler
{
    public static function AppExceptionHandle(Throwable $e): void
    {
        ErrorService::load();
    }
}

class AppException extends Exception
{

}