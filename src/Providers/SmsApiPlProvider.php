<?php

/**
 * Provider for SMSAPI: https://www.smsapi.com/en
 * Documentation: https://www.smsapi.com/docs/#sms-api-documentation-1-introduction
 * Dependency: [smsapi/php-client] https://github.com/smsapi/smsapi-php-client
 * More examples: 
 *  - https://www.smsapi.pl/blog/podstawy/biblioteka-php-sms-api/
 *  - https://www.smsapi.pl/sms-api
 *  - https://dev.to/grafstorm/writing-a-simple-custom-laravel-notification-channel-4f33
 */

declare(strict_types=1);

namespace Assghard\Laravel2fa\Providers;

use Smsapi\Client\Curl\SmsapiHttpClient;
use Smsapi\Client\Feature\Sms\Bag\SendSmsBag;

class SmsApiPlProvider
{
	public function __construct(private string $apiToken)
	{
	}
    
    /**
     * @param string $phoneNumber Example 48111222333 or 111222333
     * @param string $smsMessage
     * @return boolean Notification has been SENT (true) or NOT (false)
     */
    public function sendNotification(string $phoneNumber, string $smsMessage): bool
    {
        $sms = SendSmsBag::withMessage($phoneNumber, $smsMessage);
        $sms->encoding = 'utf-8';
        $sms->from = env('SMS_API_NAME_FORM'); // this name should been added in smsapi admin panel!
        
        $service = (new SmsapiHttpClient())->smsapiPlService($this->apiToken);

        try {
            $response = $service->smsFeature()->sendSms($sms);
            if (empty($response) || empty($response->id)) {
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
