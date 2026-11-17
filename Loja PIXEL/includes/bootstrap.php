<?php
declare(strict_types=1);
if(session_status()!==PHP_SESSION_ACTIVE){ini_set('session.use_only_cookies','1');ini_set('session.cookie_httponly','1');ini_set('session.cookie_samesite','Lax');session_start();}
require_once __DIR__.'/functions.php';
$envFile=dirname(__DIR__).'/.env';
if(is_file($envFile)){foreach(file($envFile,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $line){$line=trim($line);if($line===''||str_starts_with($line,'#')||!str_contains($line,'='))continue;[$k,$v]=explode('=',$line,2);$_ENV[trim($k)]=trim($v," \t\n\r\0\x0B\"'");}}
