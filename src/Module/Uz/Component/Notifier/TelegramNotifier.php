<?php

namespace App\Module\Uz\Component\Notifier;

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

    public function __construct(string $botId, string $botSecret)
    {
        $this->botId = $botId;
        $this->botSecret = $botSecret;
    }

    /**
     * @param string $message
     * @param array $params
     * @return void
     * @throws \Exception
     */
    public function notify(string $message, array $params = []): void
    {
        if (!isset($params['chatId'])) {
            throw new \InvalidArgumentException('Param chatId is not set');
        }

        $url = "https://api.telegram.org/bot{$this->botId}:{$this->botSecret}/sendMessage";

        $response = CurlRequest::init($url)
            ->setPostFields([
                'chat_id' => $params['chatId'],
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
    public function getName(): string
    {
        return 'telegram';
    }
}
