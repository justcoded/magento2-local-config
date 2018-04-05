<?php

namespace JustCoded\LocalConfig\Plugin\Framework\App;

use Magento\Framework\App\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use JustCoded\LocalConfig\Helper\Data as ModuleHelper;

class ConfigPlugin
{
    /**
     * @var ModuleHelper
     */
    private $moduleHelper;

    public function __construct(
        ModuleHelper $moduleHelper
    ) {
      $this->moduleHelper = $moduleHelper;
    }

    /**
     * Apply local config if it's exists
     *
     * @param Config $subject
     * @param $proceed
     * @param $path
     * @param string $scope
     * @param null $scopeCode
     * @return mixed|null
     */
    public function aroundGetValue(
        Config $subject,
        $proceed,
        $path,
        $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        $scopeCode = null
    ) {
        $value = $proceed($path, $scope, $scopeCode);

        $localValue = $this->moduleHelper->getLocalConfigValue($path);

        return $localValue !== null ? $localValue : $value;
    }
}