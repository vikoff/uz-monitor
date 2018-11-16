<?php

namespace app\components\UzMonitor\Notifier;

use app\components\curl\CurlRequest;

class TelegramNotifier implements NotifierInterface
{
    /**
     * @var string
     */
    private $botId;
    /**
     * @var string
     */
    private $botSecret;
    /**
     * @var string
     */
    private $chatId;

    /**
     * @param string $botId
     * @param string $botSecret
     * @param string $chatId
     */
    public function __construct($botId, $botSecret, $chatId)
    {
        $this->botId = $botId;
        $this->botSecret = $botSecret;
        $this->chatId = $chatId;
    }

    /**
     * @param string $message
     * @throws \Exception
     */
    public function notify($message)
    {
        $url = "https://api.telegram.org/bot{$this->botId}:{$this->botSecret}/sendMessage";

        $response = CurlRequest::init($url)
            ->setPostFields([
                'chat_id' => $this->chatId,
                'text' => $message,
            ])
            ->exec();

        $responseData = json_decode($response->getBody(), true);
        if ($responseData['ok'] !== true) {
            throw new \Exception('Telegram API fail: ' . $response->getBody());
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'telegram';
    }
}
