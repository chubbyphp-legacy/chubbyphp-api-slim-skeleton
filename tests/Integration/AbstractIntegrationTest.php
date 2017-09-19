<?php

namespace Chubbyphp\Tests\ApiSkeleton\Integration;

use PHPUnit\Framework\TestCase;
use Chubbyphp\Tests\ApiSkeleton\PhpServerListener;

abstract class AbstractIntegrationTest extends TestCase
{
    /**
     * @var resource
     */
    private $curl;

    /**
     * @param string      $method
     * @param string      $resource
     * @param array       $headers
     * @param string|null $body
     *
     * @return array
     */
    protected function httpRequest(string $method, string $resource, array $headers = [], string $body = null): array
    {
        $curlHeaders = [];
        foreach ($headers as $key => $value) {
            $curlHeaders[] = sprintf('%s: %s', $key, implode(', ', (array) $value));
        }

        if (null === $this->curl) {
            $this->curl = $this->initializeCurl();
        }

        curl_setopt($this->curl, CURLOPT_URL, sprintf(
            'http://localhost:%d/index_test.php%s',
            PhpServerListener::PHP_SERVER_PORT,
            $resource
        ));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $curlHeaders);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);

        $rawResponse = curl_exec($this->curl);
        if (false === $rawResponse) {
            throw new \RuntimeException('Invalid response from server!');
        }

        $headerSize = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);

        $headerRows = $this->getHttpHeaderRows($rawResponse, $headerSize);

        $status = $this->getHttpStatus(array_shift($headerRows));
        $headers = $this->geHttptHeaders($headerRows);

        $body = substr($rawResponse, $headerSize);
        if ('' === $body) {
            $body = null;
        }

        return ['status' => $status, 'headers' => $headers, 'body' => $body];
    }

    /**
     * @return resource
     */
    private function initializeCurl()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        return $ch;
    }

    /**
     * @param string $rawResponse
     * @param int    $headerSize
     *
     * @return array
     */
    private function getHttpHeaderRows(string $rawResponse, int $headerSize): array
    {
        $headerRawGroups = explode("\r\n\r\n", trim(substr($rawResponse, 0, $headerSize)));

        return explode("\r\n", $headerRawGroups[0]);
    }

    /**
     * @param string $statusRow
     *
     * @return array
     */
    private function getHttpStatus(string $statusRow): array
    {
        $matches = [];
        preg_match('#^HTTP/1.\d{1} (\d+) (.+)$#', $statusRow, $matches);

        return [
            'code' => (int) $matches[1],
            'message' => $matches[2],
        ];
    }

    /**
     * @param array $headerRows
     *
     * @return array
     */
    private function geHttptHeaders(array $headerRows): array
    {
        $headers = [];
        foreach ($headerRows as $headerRow) {
            if (false !== $pos = strpos($headerRow, ':')) {
                $key = strtolower(trim(substr($headerRow, 0, $pos)));
                $value = strtolower(trim(substr($headerRow, $pos + 1)));
                if ('' !== $value) {
                    if (!isset($headers[$key])) {
                        $headers[$key] = [];
                    }
                    $headers[$key][] = $value;
                }
            }
        }

        ksort($headers);

        return $headers;
    }
}
