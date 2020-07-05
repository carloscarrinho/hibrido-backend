<?php

namespace Source\Services;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\BrowserConsoleHandler;

class Log
{
    public $logger;

    public function __construct()
    {
        $this->logger = new Logger("api");

        $this->logger->pushHandler(new StreamHandler(CFG_LOG_FILE, Logger::EMERGENCY));
        $this->logger->pushHandler(new StreamHandler(CFG_LOG_FILE, Logger::ALERT));
        $this->logger->pushHandler(new StreamHandler(CFG_LOG_FILE, Logger::CRITICAL));
        $this->logger->pushHandler(new StreamHandler(CFG_LOG_FILE, Logger::ERROR));
        $this->logger->pushHandler(new StreamHandler(CFG_LOG_FILE, Logger::WARNING));
        $this->logger->pushHandler(new StreamHandler(CFG_LOG_FILE, Logger::NOTICE));
        $this->logger->pushHandler(new BrowserConsoleHandler(Logger::INFO));
        $this->logger->pushHandler(new BrowserConsoleHandler(Logger::DEBUG));

        $this->logger->pushProcessor(function ($record){
            $record["extra"]["HTTP_HOST"] = $_SERVER["HTTP_HOST"];
            $record["extra"]["REQUEST_URI"] = $_SERVER["REQUEST_URI"];
            $record["extra"]["REQUEST_METHOD"] = $_SERVER["REQUEST_METHOD"];
            $record["extra"]["HTTP_USER_AGENT"] = $_SERVER["HTTP_USER_AGENT"];
            return $record;
        });
    }

    public function emergency(string $message, array $context)
    {
        $this->logger->emergency($message, $context);
    }

    public function alert(string $message, array $context)
    {
        $this->logger->alert($message, $context);
    }

    public function critical(string $message, array $context)
    {
        $this->logger->critical($message, $context);
    }

    public function error(string $message, array $context)
    {
        $this->logger->error($message, $context);
    }
    
    public function warning(string $message, array $context)
    {
        $this->logger->warning($message, $context);
    }

    public function notice(string $message, array $context)
    {
        $this->logger->notice($message, $context);
    }
    
    public function info(string $message, array $context)
    {
        $this->logger->info($message, $context);
    }
    
    public function debug(string $message, array $context)
    {
        $this->logger->debug($message, $context);
    }
}