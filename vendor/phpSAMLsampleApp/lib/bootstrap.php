<?php
require_once(dirname(__FILE__) . "/PHPSAMLProcessor.php");
require_once(dirname(__FILE__) . "/thirdparty/log4php/Logger.php");

function errno2str($type) {
    switch($type) {
        case E_ERROR: // 1 //
            return "ERROR";
        case E_WARNING: // 2 //
            return "WARNING";
        case E_PARSE: // 4 //
            return "PARSE";
        case E_NOTICE: // 8 //
            return "NOTICE";
        case E_CORE_ERROR: // 16 //
            return "CORE_ERROR";
        case E_CORE_WARNING: // 32 //
            return "CORE_WARNING";
        case E_USER_ERROR: // 256 //
            return "ERROR";
        case E_USER_WARNING: // 512 //
            return "WARNING";
        case E_USER_NOTICE: // 1024 //
            return "NOTICE";
        case E_STRICT: // 2048 //
            return "STRICT";
        case E_RECOVERABLE_ERROR: // 4096 //
            return "RECOVERABLE_ERROR";
        case E_DEPRECATED: // 8192 //
            return "DEPRECATED";
        case E_USER_DEPRECATED: // 16384 //
            return "DEPRECATED";
        
        default:
            return "INFO";
    }
    return $type;
}

function OKTAErrorHandler($errno, $errstr, $errfile, $errline) {
    $msg = $errstr;

    if (in_array($errno, array(E_ERROR, E_PARSE, E_USER_ERROR, E_CORE_ERROR))) {
        Logger::getRootLogger()->error($msg);
        exit(1);
    } elseif (in_array($errno, array(E_WARNING, E_CORE_WARNING, E_USER_WARNING))) {
        Logger::getRootLogger()->warn($msg);
    } else {
        Logger::getRootLogger()->info($msg);
    }
    
    return true;
}

function OKTAExceptionHandler($exception) {
    Logger::getRootLogger()->error("Exception raised [" . get_class($exception) . "]: " . $exception->getMessage());
}

Logger::configure(array(
    "appenders" => array(
        "default" => array(
            "class" => "LoggerAppenderFile",
            "layout" => array(
                "class" => "LoggerLayoutPattern",
                "params" => array(
                    //"conversionPattern" => "[OKTA-PHP] %p %d{Y-m-d H:i:s} %l : %m%n"
                    "conversionPattern" => "[OKTA-PHP %p] %d{Y-m-d H:i:s} %m%n"
                ),
            ),
            "params" => array(
                "file" => Config::getLogsFilename(),
                "append" => true,
            ),
        ),
    ),
    "rootLogger" => array(
        "appenders" => array("default"),
        "level" => Config::LOGS_LEVEL,
    ),
    'threshold' => 'ALL',
));
set_error_handler("OKTAErrorHandler");
set_exception_handler("OKTAExceptionHandler");
?>
