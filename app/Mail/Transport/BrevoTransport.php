<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;
use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;

class BrevoTransport extends AbstractTransport
{
    protected $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->apiKey);
        $apiInstance = new TransactionalEmailsApi(null, $config);

        $sendSmtpEmail = new SendSmtpEmail();
        $sendSmtpEmail->setSubject($email->getSubject());
        
        // Set sender
        $fromAddresses = $email->getFrom();
        if (!empty($fromAddresses)) {
            $from = $fromAddresses[0];
            $sendSmtpEmail->setSender([
                'email' => $from->getAddress(),
                'name' => $from->getName() ?: $from->getAddress()
            ]);
        } else {
            // Use default from config
            $sendSmtpEmail->setSender([
                'email' => config('mail.from.address'),
                'name' => config('mail.from.name', config('mail.from.address'))
            ]);
        }
        
        // Set recipients
        $to = [];
        foreach ($email->getTo() as $recipient) {
            $recipientName = $recipient->getName();
            if (empty($recipientName)) {
                // Extract name from email or use email as name
                $emailParts = explode('@', $recipient->getAddress());
                $recipientName = ucfirst(str_replace('.', ' ', $emailParts[0]));
            }
            $to[] = [
                'email' => $recipient->getAddress(),
                'name' => $recipientName
            ];
        }
        $sendSmtpEmail->setTo($to);
        
        // Set content
        if ($email->getHtmlBody()) {
            $sendSmtpEmail->setHtmlContent($email->getHtmlBody());
        }
        if ($email->getTextBody()) {
            $sendSmtpEmail->setTextContent($email->getTextBody());
        }
        
        // Set reply-to if exists
        if ($email->getReplyTo()) {
            $replyTo = $email->getReplyTo()[0];
            $sendSmtpEmail->setReplyTo([
                'email' => $replyTo->getAddress(),
                'name' => $replyTo->getName() ?: $replyTo->getAddress()
            ]);
        }
        
        // Send email
        try {
            $apiInstance->sendTransacEmail($sendSmtpEmail);
        } catch (\Exception $e) {
            throw new \Exception('Brevo API Error: ' . $e->getMessage());
        }
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}