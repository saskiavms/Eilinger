<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;
use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Illuminate\Support\Facades\Log;

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
            $response = $apiInstance->sendTransacEmail($sendSmtpEmail);

            // Best-effort logging for diagnostics (non-PII where possible)
            try {
                $recipientEmails = array_map(fn ($r) => $r['email'] ?? null, $to);
                $recipientEmails = array_values(array_filter($recipientEmails));
                $messageId = method_exists($response, 'getMessageId') ? $response->getMessageId() : null;
                Log::info('Brevo email sent', [
                    'subject' => $email->getSubject(),
                    'to' => $recipientEmails,
                    'message_id' => $messageId,
                ]);
            } catch (\Throwable $t) {
                // avoid failing the mail send due to logging issues
            }
        } catch (\Exception $e) {
            try {
                $recipientEmails = array_map(fn ($r) => $r['email'] ?? null, $to);
                $recipientEmails = array_values(array_filter($recipientEmails));
                Log::error('Brevo email failed', [
                    'subject' => $email->getSubject(),
                    'to' => $recipientEmails,
                    'error' => $e->getMessage(),
                ]);
            } catch (\Throwable $t) {
                // ignore logging failure
            }
            throw new \Exception('Brevo API Error: ' . $e->getMessage());
        }
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}
