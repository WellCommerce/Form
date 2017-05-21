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

namespace WellCommerce\Component\Form\Elements;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Component\Form\Handler\FormHandlerInterface;
use WellCommerce\Component\Form\Validator\FormValidatorInterface;

/**
 * Class Form
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class Form extends AbstractContainer implements FormInterface
{
    /**
     * @var FormHandlerInterface
     */
    protected $formHandler;
    
    /**
     * @var object
     */
    protected $modelData;
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setDefaults([
            'label'                 => '',
            'action'                => '',
            'enctype'               => '',
            'ajax_enabled'          => true,
            'method'                => FormInterface::FORM_METHOD,
            'tabs'                  => FormInterface::TABS_VERTICAL,
            'validation_groups'     => FormValidatorInterface::DEFAULT_VALIDATOR_GROUPS,
            'additional_javascript' => [],
        ]);
        
        $resolver->setNormalizer('name', function (Options $options, $value) {
            return preg_replace('/[^A-Za-z0-9\-]/', '_', $value);
        });
        
        $resolver->setAllowedTypes('action', 'string');
        $resolver->setAllowedTypes('method', 'string');
        $resolver->setAllowedTypes('enctype', 'string');
        $resolver->setAllowedTypes('ajax_enabled', 'bool');
        $resolver->setAllowedTypes('tabs', 'integer');
        $resolver->setAllowedTypes('validation_groups', ['null', 'array']);
        $resolver->setAllowedTypes('additional_javascript', ['array']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function setFormHandler(FormHandlerInterface $formHandler)
    {
        $this->formHandler = $formHandler;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setModelData($modelData)
    {
        $this->modelData = $modelData;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getModelData()
    {
        return $this->formHandler->getFormModelData();
    }
    
    /**
     * {@inheritdoc}
     */
    public function handleRequest()
    {
        return $this->formHandler->handleRequest($this);
    }
    
    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return $this->formHandler->isFormValid($this);
    }
    
    /**
     * {@inheritdoc}
     */
    public function isSubmitted()
    {
        return $this->formHandler->isFormSubmitted($this);
    }
    
    /**
     * {@inheritdoc}
     */
    public function isAction($actionName)
    {
        return $this->formHandler->isFormAction($actionName);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getValidationGroups()
    {
        return $this->options['validation_groups'];
    }
    
    public function registerAdditionalJavascript(string $path)
    {
        $this->options['additional_javascript'][$path] = $path;
    }
    
    public function getAdditionalJavascript(): array
    {
        return $this->options['additional_javascript'];
    }
    
    /**
     * {@inheritdoc}
     */
    public function prepareAttributesCollection(AttributeCollection $collection)
    {
        parent::prepareAttributesCollection($collection);
        
        $collection->add(new Attribute('sFormName', $this->getName()));
        $collection->add(new Attribute('sAction', $this->getOption('action')));
        $collection->add(new Attribute('sMethod', $this->getOption('method')));
        $collection->add(new Attribute('sClass', $this->getOption('class')));
        $collection->add(new Attribute('bEnableAjax', $this->getOption('ajax_enabled')));
        $collection->add(new Attribute('iTabs', $this->getOption('tabs'), Attribute::TYPE_INTEGER));
        $collection->add(new Attribute('oValues', $this->getValue(), Attribute::TYPE_ARRAY));
        $collection->add(new Attribute('oErrors', $this->getError(), Attribute::TYPE_ARRAY));
        
        $collection->remove('sName');
        $collection->remove('fType');
    }
}
