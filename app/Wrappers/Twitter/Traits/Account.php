<?php

namespace App\Wrappers\Twitter\Traits;

/**
 * Account
 */
trait Account
{
    /**
     * getAccount
     *
     * @return object
     */
    public function getAccount()
    {
        return $this->client->get("account/verify_credentials");
    }
}
