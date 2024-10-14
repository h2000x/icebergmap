<?php

declare(strict_types=1);

namespace Justabunchof\Icebergmap\Request;

use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IcebergCsvRequester
{
    private const API_URL = 'https://usicecenter.gov/File/DownloadCurrent?pId=134';

    private string $filename;

    private string $content;

    public function __construct(
        private RequestFactory $requestFactory,
    ) {}

    /**
     * @throws \JsonException
     * @throws \RuntimeException
     */
    public function request(): string
    {
        // Additional headers for this specific request
        // See: https://docs.guzzlephp.org/en/stable/request-options.html
        $additionalOptions = [
            'headers' => ['Cache-Control' => 'no-cache'],
            'allow_redirects' => false,
        ];

        // Get a PSR-7-compliant response object
        $response = $this->requestFactory->request(
            self::API_URL,
            'GET',
            $additionalOptions,
        );

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(
                'Returned status code is ' . $response->getStatusCode(),
            );
        }

        echo "Ping (1728156454007): ". __LINE__ . "-" . __FILE__  . "<br>\n";

        if ($response->getHeaderLine('Content-Type') !== 'application/octet-stream') {
            throw new \RuntimeException(
                'The request did not return application/octet-stream data',
            );
        }
        $content_disposition = $response->getHeaderLine('Content-Disposition');
        $this->filename = $this->extractFilename($content_disposition);
        // Get the content as a string on a successful request
        $this->content = $response->getBody()->getContents();

        return (string)$response->getStatusCode();
    }

    private function extractFilename(string $content_disposition)
    {
        $parts =GeneralUtility::trimExplode(';',$content_disposition);
        foreach ($parts as $single_part) {
            if (strpos($single_part, '=') === false) {
                continue;
            }
            list($key, $value) = explode('=', $single_part);
            if ($key == 'filename') {
                return $value;
            }
        }
        die("Filename not found");
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}