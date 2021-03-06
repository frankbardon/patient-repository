<?php
namespace Accard\Bundle\TemplateBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TwigDatabaseLoaderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('twig');
        $definition->addMethodCall('setLoader', array(new Reference('accard.twig_chain_loader')));
    }
}
