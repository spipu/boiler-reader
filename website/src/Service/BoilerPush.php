<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\Buffer;
use Exception;

class BoilerPush
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * BoilerReader constructor.
     * @param Configuration $configuration
     */
    public function __construct(
        Configuration $configuration
    ) {
        $this->configuration = $configuration;
    }

    /**
     * @param Buffer $buffer
     * @return void
     * @throws Exception
     */
    public function push(Buffer $buffer): void
    {
        $fields = array(
            'username'     => $this->configuration->getPushApiName(),
            'request_time' => time(),
            'data_time'    => $buffer->getTime(),
            'rand'         => uniqid(),
            'values'       => $buffer->getData(),
        );

        $fields['token'] = sha1(implode($fields).$this->configuration->getPushApiKey());

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->configuration->getPushApiUrl());
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

            if ($this->configuration->isDisableHttpsVerify()) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            }

            $result = curl_exec($curl);
            if (!$result) {
                throw new Exception(curl_error($curl));
            }
        } finally {
            curl_close($curl);
        }
    }
}
