<?php

namespace Nubesys\Core\Services;

use Nubesys\Core\Services\Service;

class WsService extends Service {

    protected $authorizationType            = null;
    protected $authorizationBasicUser       = null;
    protected $authorizationBasicPassword   = null;

    public function __construct($p_di)
    {
        parent::__construct($p_di);
    }

    protected function generateId(){

        $pathPartes     = explode("\\", $this->getClassPath());

        $className      = strtolower(array_pop($pathPartes));

        $this->id = implode("-", array_map(function ($e){ return \Phalcon\Text::uncamelize($e);}, $pathPartes)) . "-" . $className;
    }

    protected function doCheckAuthorization(){

        $result = false;
        
        if($this->hasHeader("Authorization") && substr($this->getHeader("Authorization"),0,6) == "Bearer"){

            $this->authorizationType    = "BEARER";

            $authorizationToken         = explode(" ", $this->getHeader("Authorization"))[1];
            
            if($this->validateApiUserToken($authorizationToken)){

                $result                 = true;
            }else{

                $this->setServiceError("Invalid Authorization Token");
            }
        }elseif($this->hasHeader("Authorization") && substr($this->getHeader("Authorization"),0,5) == "Basic"){

            $this->authorizationType                    = "BASIC";
            
            if($this->hasPostParam("grant_type") && $this->getPostParam("grant_type") == "client_credentials"){

                $authorizationToken                     = explode(" ", $this->getHeader("Authorization"))[1];

                $authorizationTokenParts                = explode(":", base64_decode($authorizationToken));

                $this->authorizationBasicUser           = $authorizationTokenParts[0];
                $this->authorizationBasicPassword       = $authorizationTokenParts[1];

                $result                                 = true;
            }else{

                $this->setServiceError("Invalid grant_type");
            }
        }else{

            $this->setServiceError("Invalid Authorization");
        }

        return $result;
    }

    public function generateApiUserToken($p_data){

        $secret                     = $this->getDI()->get('config')->crypt->privatekey;
        $time                       = 5;
        
        $result                     = base64_encode(\Nubesys\Core\Utils\Utils::jwtGenerate($secret, $p_data, $time));

        $cacheKey                   = 'api_user_' . sha1($result);
        $cacheLifetime              = $time * 65;

        $this->setCache($cacheKey, $p_data, $cacheLifetime);
        
        return $result;
    }

    public function validateApiUserToken($p_apiToken){
        $result                     = false;

        $secret                     = $this->getDI()->get('config')->crypt->privatekey;
        $time                       = 5;

        $cacheKey                   = 'api_user_' . sha1($p_apiToken);
        $cacheLifetime              = $time * 65;

        if($this->hasCache($cacheKey)){

            $data                   =  $this->getCache($cacheKey);

            $result                 = \Nubesys\Core\Utils\Utils::jwtValidate($secret, $data, base64_decode($p_apiToken));
        }
        
        return $result;
    }
}