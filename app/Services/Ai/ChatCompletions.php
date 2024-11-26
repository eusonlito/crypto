<?php declare(strict_types=1);

namespace App\Services\Ai;

class ChatCompletions extends AiAbstract
{
    /**
     * @var string
     */
    protected string $model;

    /**
     * @var int
     */
    protected int $maxTokens = 0;

    /**
     * @var array
     */
    protected array $messages;

    /**
     * @var array
     */
    protected array $response;

    /**
     * @return self
     */
    public function __construct()
    {
        $this->setConfig();
    }

    /**
     * @return void
     */
    protected function setConfig(): void
    {
        $this->config = config('ai.openai');
        $this->model = $this->config['model'];
    }

    /**
     * @param string $model
     *
     * @return self
     */
    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param int $max_tokens
     *
     * @return self
     */
    public function setMaxTokens(int $max_tokens): self
    {
        $this->maxTokens = $max_tokens;

        return $this;
    }

    /**
     * @param string $role
     * @param string $content
     *
     * @return self
     */
    public function setMessage(string $role, string $content): self
    {
        $this->messages[] = [
            'role' => $role,
            'content' => $content,
        ];

        return $this;
    }

    /**
     * @param array $messages
     *
     * @return self
     */
    public function setMessages(array $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @return self
     */
    public function send(): self
    {
        $this->response = $this->request();

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->response['choices'][0]['message']['content'];
    }

    /**
     * @return array
     */
    public function getUsage(): array
    {
        return $this->response['usage'];
    }

    /**
     * @return array
     */
    protected function request(): array
    {
        return $this->curl()
            ->setUrl('https://api.openai.com/v1/chat/completions')
            ->setBody($this->requestBody())
            ->setJson()
            ->send()
            ->getBody('array');
    }

    /**
     * @return array
     */
    protected function requestBody(): array
    {
        return array_filter([
            'model' => $this->model,
            'messages' => $this->requestBodyMessages(),
            'max_completion_tokens' => $this->maxTokens,
        ]);
    }

    /**
     * @return array
     */
    protected function requestBodyMessages(): array
    {
        if (str_starts_with($this->model, 'o1') === false) {
            return $this->messages;
        }

        return array_map(static fn ($message) => [
            'role' => 'user',
            'content' => $message['content'],
        ], $this->messages);
    }
}
