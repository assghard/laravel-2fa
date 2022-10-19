<?php

/**
 * https://www.smsapi.com/en
 */

declare(strict_types=1);

namespace Assghard\Laravel2fa\Providers;

use Smsapi\Client\SmsapiHttpClient;
use Smsapi\Client\Feature\Sms\Bag\SendSmsBag;

class SmsApiPlProvider
{
	public function __construct(
        private string $apiToken
    )
	{
	}
    
    /**
     * @param string $phoneNumber Example 48111222333
     * @param string $smsMessage
     * @return boolean
     */
    public function sendNotification(string $phoneNumber, string $smsMessage): bool
    {
        $sms = SendSmsBag::withMessage($phoneNumber, $smsMessage);
        $sms->encoding = 'utf-8';
        $sms->from = env('APP_NAME');
        
        $service = (new SmsapiHttpClient())->smsapiPlService($this->apiToken);

        try {
            $response = $service->smsFeature()->sendSms($sms);
            if (empty($response) || empty($response->id)) {
                return false;
            }

            // TODO: TEST it and check what is $response
            // TODO: handle statuses here

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
