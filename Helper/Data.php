<?php

namespace JustCoded\LocalConfig\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\HTTP\Client\Curl;

class Data extends AbstractHelper
{
    const XML_PATH_CONFIG_FILENAME = 'justcoded_local_config/settings/config_filename';
    const XML_PATH_CONFIG_URL      = 'justcoded_local_config/settings/config_url';

    /**
     * @var CurlFactory
     */
    protected $curlFactory;

    public function __construct(
        Context $context,
        CurlFactory $curlFactory
    ) {
        $this->curlFactory = $curlFactory;

        parent::__construct($context);
    }

    /**
     * @var array|null
     */
    protected $localConfig = null;

    /**
     * @param $content
     * @return array
     */
    private function getLocalConfigFromFileContent($content)
    {
        $lines  = array_filter(explode(PHP_EOL, $content));
        $config = [];

        foreach ($lines as $line) {
            if (strpos($line, '=') !== false) {
                list($name, $value) = array_map('trim', explode('=', $line, 2));

                $config[$name] = $value;
            }
        }

        return $config;
    }

    /**
     * @return string
     */
    public function getConfigFilename()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONFIG_FILENAME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getConfigUrl()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONFIG_URL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array
     */
    private function loadFileLocalConfig()
    {
        $localConfig = [];
        $configFileName = BP . DIRECTORY_SEPARATOR . $this->getConfigFilename();

        if (file_exists($configFileName) && is_file($configFileName)) {
            $configRaw = file_get_contents($configFileName);
            $localConfig = $this->getLocalConfigFromFileContent($configRaw);
        }

        return $localConfig;
    }

    private function loadUrlLocalConfig()
    {
        $localConfig = [];

        $requestUrl = $this->getConfigUrl();

        if (!$requestUrl) {
            return $localConfig;
        }

        /** @var Curl $http */
        $http = $this->curlFactory->create();
        $http->get($requestUrl);
        $responseBody = $http->getBody();

        $localConfig = $this->getLocalConfigFromFileContent($responseBody);

        return $localConfig;
    }

    /**
     * @return array|null
     */
    public function getLocalConfig()
    {
        if (!$this->localConfig) {
            $this->localConfig = array_merge($this->loadUrlLocalConfig(), $this->loadFileLocalConfig());
        }

        return $this->localConfig;
    }

    /**
     * Check is it module config, we don't need to override our self
     *
     * @param $path
     * @return bool
     */
    protected function isModuleSystemConfig($path)
    {
        return $path === self::XML_PATH_CONFIG_FILENAME || $path === self::XML_PATH_CONFIG_URL;
    }

    /**
     * @param $path
     * @return mixed|null
     */
    public function getLocalConfigValue($path)
    {
        if ($this->isModuleSystemConfig($path)) {
            return null;
        }

        $config = $this->getLocalConfig();

        return isset($config[$path]) ? $config[$path] : null;
    }
}