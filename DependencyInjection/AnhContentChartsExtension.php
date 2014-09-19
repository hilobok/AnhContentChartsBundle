<?php

namespace Anh\ContentChartsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AnhContentChartsExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('assetic', array(
            'assets' => array(
                'anh_content_charts_js' => array(
                    'inputs' => array(
                        'bundles/anhcontentcharts/components/highcharts/highcharts.js',
                        '@AnhContentChartsBundle/Resources/assets/js/init.js',
                    ),
                ),
                'anh_content_editor_tags_css' => array(
                    'inputs' => array(
                        '@AnhContentChartsBundle/Resources/assets/css/editor-tags.css',
                    ),
                ),
                'anh_content_editor_options_js' => array(
                    'inputs' => array(
                        '@AnhContentChartsBundle/Resources/assets/js/editor-options.js',
                    ),
                ),
            ),
            'bundles' => array(
                'AnhContentChartsBundle',
            )
        ));

        $container->prependExtensionConfig('sp_bower', array(
            'assetic' => array(
                'enabled' => false,
            ),
            'bundles' => array(
                'AnhContentChartsBundle' => null,
            ),
        ));
    }
}
