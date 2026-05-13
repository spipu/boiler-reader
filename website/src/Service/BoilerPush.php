<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Buffer;
use Exception;

class BoilerPush
{
    private ConfigurationService $configurationService;

    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    /**
     * @throws Exception
     */
    public function push(Buffer $buffer): void
    {
        $fields = [
            'username'     => $this->configurationService->getPushApiName(),
            'request_time' => time(),
            'data_time'    => $buffer->getTime(),
            'rand'         => uniqid(),
            'values'       => $buffer->getData(),
        ];

        $fields['token'] = sha1(implode($fields) . $this->configurationService->getPushApiKey());

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->configurationService->getPushApiUrl());
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

        $result = curl_exec($curl);
        if (!$result) {
            throw new Exception(curl_error($curl));
        }
        $result = trim($result);
        if ($result !== 'OK') {
            throw new Exception($result);
        }
    }
}
