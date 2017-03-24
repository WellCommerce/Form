<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Component\Form\Elements\Progress;

use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Component\Form\Elements\Attribute;
use WellCommerce\Component\Form\Elements\AttributeCollection;
use WellCommerce\Component\Form\Elements\ElementInterface;

/**
 * Class ConsoleOutput
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ConsoleOutput extends AbstractProgressField implements ElementInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setRequired([
            'run_url',
            'button_label',
        ]);
        
        $resolver->setAllowedTypes('run_url', 'string');
        $resolver->setAllowedTypes('button_label', 'string');
    }
    
    /**
     * {@inheritdoc}
     */
    public function prepareAttributesCollection(AttributeCollection $collection)
    {
        parent::prepareAttributesCollection($collection);
        $collection->add(new Attribute('sRunUrl', $this->getOption('run_url')));
        $collection->add(new Attribute('sButtonLabel', $this->getOption('button_label')));
    }
}
