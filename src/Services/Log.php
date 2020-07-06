<?php

namespace Source\Services;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\BrowserConsoleHandler;

/**
 * Log Class | Responsável por abstrair o controle de logs da aplicação com base
 * nas recomendações da "PSR-3 Logger Interface"
 */
class Log
{
    public $logger;
    
    /**
     * Método construtor da classe que abstrai as funcionalidades do componente Monolog
     *
     * @return void
     */
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
    
    /**
     * Método que lança os logs classificados como "emergency"
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function emergency(string $message, array $context)
    {
        $this->logger->emergency($message, $context);
    }
    
    /**
     * Método que lança os logs classificados como "alert"
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function alert(string $message, array $context)
    {
        $this->logger->alert($message, $context);
    }
    
    /**
     * Método que lança os logs classificados como "critical"
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function critical(string $message, array $context)
    {
        $this->logger->critical($message, $context);
    }
    
    /**
     * Método que lança os logs classificados como "error"
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function error(string $message, array $context)
    {
        $this->logger->error($message, $context);
    }
        
    /**
     * Método que lança os logs classificados como "warning"
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function warning(string $message, array $context)
    {
        $this->logger->warning($message, $context);
    }
    
    /**
     * Método que lança os logs classificados como "notice"
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function notice(string $message, array $context)
    {
        $this->logger->notice($message, $context);
    }
        
    /**
     * Método que lança os logs classificados como "info"
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function info(string $message, array $context)
    {
        $this->logger->info($message, $context);
    }
        
    /**
     * Método que lança os logs classificados como "debug"
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function debug(string $message, array $context)
    {
        $this->logger->debug($message, $context);
    }
}