<?php

namespace App\Service;

use Twilio\Rest\Client;

class SendSmsService
{
    private $twilio;
    private $twilioPhoneNumber;

    public function __construct(string $twilioSid, string $twilioAuthToken, string $twilioPhoneNumber)
    {
        $this->twilio = new Client($twilioSid, $twilioAuthToken);
        $this->twilioPhoneNumber = $twilioPhoneNumber;
    }

    public function sendSms(string $to, string $message): void
    {
        // Assurez-vous que le numéro est au format E.164
        if (strpos($to, '+') !== 0) {
            // Convertir le numéro en format international si nécessaire
            $to = '+33' . ltrim($to, '0'); // Remplacez '+33' par le code pays approprié
        }

        $this->twilio->messages->create(
            $to,
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $message,
            ]
        );
    }
}
