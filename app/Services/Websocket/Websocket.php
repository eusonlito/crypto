<?php declare(strict_types=1);

namespace App\Services\Websocket;

use Closure;
use Exception;
use stdClass;

class Websocket
{
    /**
     * @var string
     */
    protected string $uri;

    /**
     * @var string
     */
    protected string $host;

    /**
     * @var int
     */
    protected int $port;

    /**
     * @var string
     */
    protected string $protocol;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var array
     */
    protected array $headers = [];

    /**
     * @var int
     */
    protected int $timeout = 1;

    /**
     * @var bool
     */
    protected bool $log = false;

    /**
     * @var resource
     */
    protected $stream;

    /**
     * @param string $uri
     * @param string $path = '/'
     *
     * @return self
     */
    public function __construct(string $uri, string $path = '/')
    {
        $this->parse($uri);
        $this->path = $path;
    }

    /**
     * @param string $uri
     *
     * @return self
     */
    protected function parse(string $uri): self
    {
        $this->uri = $uri;

        $uri = parse_url($uri);

        $this->setHost($uri['host']);
        $this->setProtocol($uri['scheme']);

        if (isset($uri['port'])) {
            $this->setPort((int)$uri['port']);
        } elseif (in_array($uri['scheme'], ['https', 'wss'])) {
            $this->setPort(443);
        } else {
            $this->setPort(80);
        }

        return $this;
    }

    /**
     * @param string $host
     *
     * @return self
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param int $port
     *
     * @return self
     */
    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @param string $protocol
     *
     * @return self
     */
    public function setProtocol(string $protocol): self
    {
        if (in_array($protocol, ['https', 'wss'])) {
            $this->protocol = 'ssl://';
        }

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return self
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param int $timeout
     *
     * @return self
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @param bool $log
     *
     * @return self
     */
    public function setLog(bool $log): self
    {
        $this->log = $log;

        return $this;
    }

    /**
     * @return self
     */
    public function open(): self
    {
        $address = $this->protocol.$this->host.':'.$this->port;

        $this->logFile('connect', $address);

        $this->stream = stream_socket_client($address, $errno, $errstr, $this->timeout, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT);

        if ($this->stream === false) {
            throw new Exception(sprintf('Unable to connect to websocket server: %s (%s).', $errstr, $errno));
        }

        $request = $this->openRequest();

        $this->logFile('request', $request);

        stream_set_timeout($this->stream, $this->timeout);

        if (fwrite($this->stream, $request) === false) {
            throw new Exception(sprintf('Unable to send upgrade header to websocket server: %s (%s).', $errstr, $errno));
        }

        fread($this->stream, 1024);

        return $this;
    }

    /**
     * @return string
     */
    protected function openRequest(): string
    {
        $headers = $this->headers + [
            'Host' => $this->host,
            'Pragma' => 'no-cache',
            'Upgrade' => 'WebSocket',
            'Connection' => 'Upgrade',
            'Sec-WebSocket-Key' => base64_encode(openssl_random_pseudo_bytes(16)),
            'Sec-WebSocket-Version' => '13',
        ];

        $header = '';

        foreach ($headers as $key => $value) {
            $header .= "{$key}: {$value}\r\n";
        }

        return "GET {$this->path} HTTP/1.1\r\n{$header}\r\n";
    }

    /**
     * @param string $data
     *
     * @return self
     */
    public function write(string $data): self
    {
        $this->logFile('write', $data);

        $length = strlen($data);
        $header = chr(0x80 | 0x01);

        if ($length < 126) {
            $header .= chr(0x80 | $length);
        } elseif ($length < 0xFFFF) {
            $header .= chr(0x80 | 126).pack('n', $length);
        } else {
            $header .= chr(0x80 | 127).pack('N', 0).pack('N', $length);
        }

        $mask = pack('N', rand(1, 0x7FFFFFFF));
        $header .= $mask;

        for ($i = 0; $i < $length; $i++) {
            $data[$i] = chr(ord($data[$i]) ^ ord($mask[$i % 4]));
        }

        fwrite($this->stream, $header.$data);

        return $this;
    }

    /**
     * @param \Closure $callback
     *
     * @return self
     */
    public function read(Closure $callback): self
    {
        while (feof($this->stream) === false) {
            $callback($this->readPayload());
        }

        return $this;
    }

    /**
     * @return ?\stdClass
     */
    protected function readPayload(): ?stdClass
    {
        if (stream_get_contents($this->stream, 1) === '') {
            return null;
        }

        $maskAndLength = stream_get_contents($this->stream, 1);
        $maskAndLength = array_values(unpack('C', $maskAndLength))[0];

        $length = $maskAndLength & 127;
        $maskSet = boolval($maskAndLength & 128);

        if ($length === 126) {
            $payloadLength = stream_get_contents($this->stream, 2);
            $payloadLength = array_values(unpack('n', $payloadLength))[0];
        } elseif ($length === 127) {
            $payloadLength = stream_get_contents($this->stream, 8);

            [$higher, $lower] = array_values(unpack('N2', $payloadLength));

            $payloadLength = $higher << 32 | $lower;
        } else {
            $payloadLength = $length;
        }

        if ($maskSet) {
            $mask = stream_get_contents($this->stream, 4);
        }

        $payload = stream_get_contents($this->stream, $payloadLength);

        if ($maskSet) {
            $payload = $this->unmask($mask, $payload);
        }

        $this->logFile('read', $payload);

        return json_decode($payload);
    }

    /**
     * @param string $mask
     * @param string $data
     *
     * @return string
     */
    protected function unmask(string $mask, string $data): string
    {
        $payload = '';
        $length = strlen($data);

        for ($i = 0; $i < $length; $i++) {
            $payload .= $data[$i] ^ $mask[$i % 4];
        }

        return $payload;
    }

    /**
     * @param string $action
     * @param string $payload
     *
     * @return void
     */
    protected function logFile(string $action, string $payload): void
    {
        if ($this->log !== true) {
            return;
        }

        $dir = storage_path('logs/websocket/'.date('Y-m-d'));
        $file = str_slug($this->host).'.json';

        clearstatcache(true, $dir);

        if (is_dir($dir) === false) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($dir.'/'.$file, json_encode([$action => $payload])."\n", FILE_APPEND | LOCK_EX);
    }
}
