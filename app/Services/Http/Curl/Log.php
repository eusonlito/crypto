<?php declare(strict_types=1);

namespace App\Services\Http\Curl;

class Log
{
    /**
     * @param string $url
     * @param string $status
     * @param array $data
     * @param array $hide = []
     *
     * @return void
     */
    public static function write(string $url, string $status, array $data, array $hide = []): void
    {
        $dir = storage_path('logs/curl/'.date('Y/m/d'));

        $file = preg_replace(['/[^a-z0-9\-]/', '/\-{2,}/'], ['-', '-'], strtolower($url));
        $file = date('H-i-s').'-'.sprintf('%.4f', microtime(true)).'-'.$status.'-'.substr($file, 0, 200).'.json';

        $data = static::data($data, $hide);

        helper()->mkdir($dir);

        file_put_contents($dir.'/'.$file, helper()->jsonEncode($data), LOCK_EX);
    }

    /**
     * @param array $data
     * @param array $hide
     *
     * @return array
     */
    protected static function data(array $data, array $hide): array
    {
        $hide = array_map('strtolower', $hide);

        if (array_key_exists('headers', $data)) {
            $data['headers'] = static::hide($data['headers'], $hide);
        }

        if (array_key_exists('body', $data) && is_array($data['body'])) {
            $data['body'] = static::hide($data['body'], $hide);
        }

        if (array_key_exists('query', $data)) {
            $data['query'] = static::hide($data['query'], $hide);
        }

        return $data;
    }

    /**
     * @param array $data
     * @param array $hide
     *
     * @return array
     */
    protected static function hide(array $data, array $hide): array
    {
        foreach (array_keys($data) as $data_key) {
            $data_key_lower = strtolower($data_key);

            foreach ($hide as $hide_key) {
                if ($data_key_lower !== $hide_key) {
                    continue;
                }

                $data[$data_key] = 'HIDDEN';
                break;
            }
        }

        return $data;
    }
}
