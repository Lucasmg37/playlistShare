<?php


namespace App\Integrations;

use App\Model\WebService;
use App\Util\Helper;
use App\Util\Token;
use Bootstrap\Config as BootstrapConfig;



class InternalRequests
{
  public $params;
  public $route;

  public function __construct(string $route, array $params = [])
  {
    $this->setRoute($route);
    $this->setParams($params);
  }

  public function execute()
  {

    $config = new BootstrapConfig();

    $params  = $this->getParams();
    $params["st_key_internal_requests"] = $config->getConfig("st_key_internal_requests");

    $ws = new WebService();
    $ws->setHeader("Authorization: Bearer" .  Token::getTokenByAuthorizationHeader());
    $ws->setEndpoint("http://localhost/projects/playlistShare/backend/public/Api/" . $this->getRoute() . Helper::arrayToQuery($params));
    $ws->setTimeOut(1);
    $retorno =  $ws->get();
    return $retorno;
  }

  public function setRoute(string $route)
  {
    $this->route = $route;
  }

  public function getRoute()
  {
    return $this->route;
  }

  public function setParams(array $params)
  {
    $this->params = $params;
  }

  public function getParams()
  {
    return $this->params;
  }
}
