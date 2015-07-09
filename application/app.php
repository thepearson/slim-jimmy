<?php
use \Models;

$site_config = array(
  'database'      => array(
    'dbname'        => '',
    'user'          => '',
    'password'      => '',
    'host'          => '',
    'driver'        => 'pdo_mysql',
  ),
  'templates'     => __DIR__ . '/templates',
  'middleware'    => array(
    'expires'       => '20 minutes',
    'path'          => '/',
    'domain'        => NULL,
    'secure'        => FALSE,
    'httponly'      => FALSE,
    'name'          => 'slim_session',
    'secret'        => 'CHANGE_ME',
    'cipher'        => MCRYPT_RIJNDAEL_256,
    'cipher_mode'   => MCRYPT_MODE_CBC
  ),
  'view_options'  => array(
    'debug'         => TRUE,
    'cache'         => __DIR__ . '/cache',
  )
);

$config = new \Doctrine\DBAL\Configuration();
$conn = \Doctrine\DBAL\DriverManager::getConnection($site_config['database'], $config);

$app = new \Slim\Slim(array('view' => new \Slim\Views\Twig()));
$app->add(new \Slim\Middleware\SessionCookie($site_config['templates']));

$view = $app->view();

// Set some TwiG options
$view->parserOptions = $site_config['view_options'];

// Allow for twig/slim interaction like URLFor
$view->parserExtensions = array(new \Slim\Views\TwigExtension());

// Set template dir.
$view->setTemplatesDirectory($site_config['templates']);

// include routes
require __DIR__ . '/routes.php';

$app->run();