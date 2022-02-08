<?php

namespace Nubesys\Auth\Ws;

use Nubesys\Auth\Services\SecureWsService;
use Nubesys\Auth\User;

class ApiAuth extends SecureWsService
{

    public function tokenMethod()
    {
        
        if($this->authorizationType == "BASIC"){

            if($this->authorizationBasicUser != null && $this->authorizationBasicPassword != null){

                $userManager                = new User($this->getDI());
                $userData                   = $userManager->loginApiUser($this->authorizationBasicUser, $this->authorizationBasicPassword);
                    
                if ($userData) {

                    $apiTokenData               = array();
                    $apiTokenData["userId"]     = $userData['id'];
                    $apiTokenData["userModel"]  = $userData['model'];

                    $apiToken                   = $this->generateApiUserToken($apiTokenData);

                    $result = array();
                    $result["APITOKEN"]         = $apiToken;

                    $this->setServiceSuccess($result);

                } else {

                    $this->setServiceError("Invalid User", 403);
                }
            }
        }
    }
}
