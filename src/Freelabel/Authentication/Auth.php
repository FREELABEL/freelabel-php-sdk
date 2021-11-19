<?php
namespace Freelabel\Authentication;

class Auth {

    private $accessToken;
    public function __construct($accessToken) {
        $this->accessToken = $accessToken;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    
    

}
