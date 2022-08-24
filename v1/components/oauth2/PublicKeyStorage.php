<?php

namespace v1\components\oauth2;

use OAuth2\Storage\PublicKeyInterface;
use common\helpers\Path;

class PublicKeyStorage implements PublicKeyInterface
{
    private $pbk = null;
    private $pvk = null;

    public function __construct()
    {
        $this->pvk = file_get_contents(Path::join(dirname(__FILE__), 'pvk.pem'));
        $this->pbk = file_get_contents(Path::join(dirname(__FILE__), 'pbk.pem'));
    }

    /**
     * @inheritDoc
     */
    public function getPublicKey($client_id = null)
    {
        return $this->pbk;
    }

    /**
     * @inheritDoc
     */
    public function getPrivateKey($client_id = null)
    {
        return $this->pvk;
    }

    /**
     * @inheritDoc
     */
    public function getEncryptionAlgorithm($client_id = null)
    {
        return 'RS256';
    }
}
