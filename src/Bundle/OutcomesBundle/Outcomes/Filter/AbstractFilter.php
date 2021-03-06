<?php

/**
 * This file is part of the Accard package.
 *
 * (c) University of Pennsylvania
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Accard\Bundle\OutcomesBundle\Outcomes\Filter;

use Accard\Bundle\OutcomesBundle\Outcomes\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Abstract filter.
 *
 * @author Frank Bardon Jr. <bardonf@upenn.edu>
 */
abstract class AbstractFilter implements FilterInterface
{
    /**
     * Get a list of options and their parameters.
     *
     * @return array
     */
    public function getOptions()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    final public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "name" => $this->getName(),
            "class" => get_class($this),
            "types" => $this->respondsTo(),
        ));

        $resolver->setRequired(array("name", "class", "types"));
        $this->setDefaultOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    final public function resolveOptions(OptionsResolverInterface $resolver, array $options = null)
    {
        return $resolver->resolve((array) $options);
    }

    /**
     * Set default filter options.
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }
}
