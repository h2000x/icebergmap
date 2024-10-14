<?php

namespace Justabunchof\Icebergmap\Service;

use Symfony\Component\HttpClient\HttpClient;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use const _PHPStan_4d77e98e1\__;

class CheckAndReadCsvService
{

    protected bool $isNew = false;
    
    protected string $extensionKey;

    protected string $data_path;
    protected string $csv_path;

    private const API_URL = 'https://usicecenter.gov/File/DownloadCurrent?pId=134';

    private const LAST_FILE_FILE = 'last_file_filename.dat';

    private const CSV_DIR = 'csv/';

    protected string $last_filename_file;

    protected string $filename;

    public function __construct(
        private RequestFactory $requestFactory,
        private readonly Registry $registry,
    ) {
        $this->extensionKey = GeneralUtility::camelCaseToLowerCaseUnderscored(explode('\\', $this::class )[1] );
        $varPath = Environment::getVarPath();
        $this->data_path = $varPath . '/' . strtolower($this->extensionKey) . '/';
        $this->last_filename_file = $this->data_path . self::LAST_FILE_FILE;

        $this->csv_path = $this->data_path . self::CSV_DIR;
    }

    public function readCsvFileFromApi()
    {
        $this->checkCreateWorkingDir();

        $additionalOptions = [
            'headers' => ['Cache-Control' => 'no-cache'],
            'allow_redirects' => true,
        ];
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

        $filename = $this->getFilename( $response->getHeaderLine('Content-Disposition'));
        if (!$this->isNewFilename($filename)){
            return false;
        }
        $this->filename = $filename;
        return $this->stringToCsvArray($response->getBody()->getContents());
    }

    public function readCsvFiles()
    {
        $result = [];
        $dir_handle = opendir($this->csv_path);
        while (($file = readdir($dir_handle)) !== false) {
            if (filetype($this->csv_path . $file) == 'file') {
                $result[] = $file;
            }
        }
        closedir($dir_handle);
        return $result;
    }

    private function getFilename(string $headerLines)
    {
        $filenameLine = $this->extractFilename($headerLines);
        list($varname,$filename) = explode('=',$filenameLine);
        return $filename;
    }

    private function extractFilename(string $headerLines)
    {
        $parts = explode(';', $headerLines);
        foreach ($parts as $part){
            if (str_contains($part, 'filename') === true) {
                if (str_contains($part, '*') === true) {
                    continue;
                }
                return $part;
            }
        }
    }

    private function checkCreateWorkingDir()
    {
        if (!is_dir($this->data_path)) {
            mkdir($this->data_path);
        }
    }

    private function isNewFilename(string $filename)
    {

        if(!is_file($this->last_filename_file)) {
            return true;
        }
        $last_filename = file_get_contents($this->last_filename_file);
        if ($filename == $last_filename) {
            return false;
        }
    }

    public function readCsvFileFromDir(mixed $single_csv_file)
    {
        $content = file_get_contents($this->csv_path . $single_csv_file);
        $array = $this->stringToCsvArray($content);
        return $array;
    }

    //TODO das muss noch gemacht werden.
    private function saveFilename(string $filename)
    {
        file_put_contents($this->last_filename_file,$filename);
    }

    private function stringToCsvArray(string $contents)
    {
        $array = preg_split("/\r\n|\n|\r/", $contents);
        $csvArray = [];
        foreach ($array as $single_line) {
            $csvArray[] = str_getcsv($single_line);
        }
        if(count(end($csvArray)) == 1) {
            $last_element = array_pop($csvArray);
        }
        return $csvArray;
    }

    private function getLastFilename()
    {

    }
}