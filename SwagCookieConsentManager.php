<?php

namespace SwagCookieConsentManager;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use SwagCookieConsentManager\Bootstrap\Installer;
use SwagCookieConsentManager\Bootstrap\Uninstaller;
use Symfony\Component\ClassLoader\Psr4ClassLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

$loader = new Psr4ClassLoader();
$loader->addPrefix('Shopware\Bundle\CookieBundle', __DIR__ . '/Bundle/CookieBundle/');
$loader->register();

class SwagCookieConsentManager extends Plugin
{
    public function build(ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator($this->getPath() . '/Bundle/'));
        $loader->load('CookieBundle/DependencyInjection/services.xml');

        parent::build($container);
    }

    public function install(InstallContext $context)
    {
        $installer = new Installer($context, $this->container->get('dbal_connection'));
        $installer->install();
    }

    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache([
            InstallContext::CACHE_TAG_CONFIG,
            InstallContext::CACHE_TAG_HTTP,
            InstallContext::CACHE_TAG_TEMPLATE,
            InstallContext::CACHE_TAG_THEME,
            InstallContext::CACHE_TAG_PROXY
        ]);
    }

    public function uninstall(UninstallContext $context)
    {
        $uninstaller = new Uninstaller($context, $this->container->get('dbal_connection'), $context->assertMinimumVersion('5.6.3'));
        $uninstaller->uninstall();

        $context->scheduleClearCache([
            InstallContext::CACHE_TAG_CONFIG,
            InstallContext::CACHE_TAG_HTTP,
            InstallContext::CACHE_TAG_TEMPLATE,
            InstallContext::CACHE_TAG_THEME,
            InstallContext::CACHE_TAG_PROXY
        ]);
    }
}