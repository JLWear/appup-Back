<?php

namespace App\Services;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

class SendinBlueService
{
    public function sendEmail($toEmail, $toName, $subject, $htmlContent)
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', env('SENDINBLUE_API_KEY'));
        $apiInstance = new TransactionalEmailsApi(new Client(), $config);

        $sendSmtpEmail = new SendSmtpEmail([
            'to' => [[ 'email' => $toEmail, 'name' => $toName ]],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
            'sender' => [
                'name' => 'Ynov',
                'email' => 'thomas.noel612@gmail.com'
            ],
        ]);

        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            return $result;
        } catch (\Exception $e) {
            throw new \Exception('Exception when calling TransactionalEmailsApi->sendTransacEmail: ' . $e->getMessage());
        }
    }
}
