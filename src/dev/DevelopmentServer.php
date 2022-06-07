<?php

declare(strict_types=1);
namespace dev;

define('SERVER_ROOT',$_SERVER['DOCUMENT_ROOT']);
define('URI',$_SERVER['REQUEST_URI']);

class DevelopmentServer {

  private $publicDir;
  private $resourcePath;

  public function __construct(
    $publicDir
    )
  {
    $this->publicDir = $publicDir;
    $this->createResourcePath();
  }

  public function createResourcePath()
  {
    $endPoints = $this->uriToArray();
    $this->resourcePath = SERVER_ROOT.$this->publicDir.implode('/',$endPoints);
    return;
  }

  private static function uriToArray()
  {
    return explode('/',explode('?',URI)[0]);
  }

  public function resourceRouting()
  {

    if (null===$this->getFileTypeRequest()) {
      $this->resourcePath = $this->resourcePath.'.php';
    }

    if ('widget_app_js'===$this->getFileTypeRequest()) {
        $this->resourcePath = SERVER_ROOT.'/theme/widget/app.js.php';
    }

    if ('widget_lib_css'===$this->getFileTypeRequest()) {
        $this->resourcePath = SERVER_ROOT.'/theme/widget/lib.css.php';
    }

    if (!file_exists($this->resourcePath)) {
      http_response_code(404);
      header('Content-Type: application/json');
      echo '{"error":"Route not found"}';
      exit();
    }

  }

  public function serve()
  {
    $this->resourceRouting();
    $this->setContentType();
    require $this->resourcePath;
  }

  public function getFileTypeRequest()
  {
    $endPoints = $this->uriToArray();
    $resource = array_pop($endPoints);
    $endPoint = explode('.',$resource);

    if ($resource==='app.js') {
        return 'widget_app_js';
    }

    if ($resource==='lib.css') {
        return 'widget_lib_css';
    }

    if (!isset($endPoint[1])) {
      return null;
    }
    return $endPoint[1];
  }

  public function setContentType()
  {

    if ('widget_app_js'===$this->getFileTypeRequest()) {
      header('Content-Type: text/javascript');
      return;
    }

    if ('widget_lib_css'===$this->getFileTypeRequest()) {
      header('Content-Type: text/css');
      return;
    }

    switch ($this->getFileTypeRequest()) {
      case 'json':
        header('Content-Type: application/json');
        break;
      case 'png':
        header('Content-Type: image/png');
        break;
      case 'gif':
        header('Content-Type: image/gif');
        break;
      case 'tiff':
        header('Content-Type: image/tiff');
        break;
      case 'jpg':
        header('Content-Type: image/jpeg');
        break;
      case 'jpeg':
        header('Content-Type: image/jpeg');
        break;
      case 'css':
        header('Content-Type: text/css');
        break;
      case 'csv':
        header('Content-Type: text/csv');
        break;
      case 'html':
        header('Content-Type: text/html');
        break;
      case 'xml':
        header('Content-Type: text/xml');
        break;
      case 'txt':
        header('Content-Type: text/plain');
        break;
      default:
        // code...
        break;
    }

  }

}
