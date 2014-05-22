<?php
/*
 * This file is part of the ByscriptsFormHandlerBundle package.
 *
 * (c) Thierry Goettelmann <thierry@byscripts.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Byscripts\Bundle\FormHandlerBundle\Form\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractFormHandler
 *
 * @author Thierry Goettelmann <thierry@byscripts.info>
 */
abstract class AbstractFormHandler
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FormTypeInterface
     */
    protected $formType;

    /**
     * @var string
     */
    protected $formTypeClass;

    /**
     * @var bool
     */
    protected $processed = false;

    /**
     * @var bool
     */
    protected $prepared = false;

    /**
     * @param string|object $formType
     *
     * @throws \Exception
     */
    public function __construct($formType)
    {
        $this->setFormType($formType);
    }

    /**
     * @param FormFactoryInterface $factory
     */
    public function setFormFactory(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @param mixed $data
     * @param array $formTypeArguments
     * @param array $formOptions
     *
     * @return $this|bool
     */
    public function prepare($data = null, array $formTypeArguments = array(), array $formOptions = array())
    {
        if (null === $this->request) {
            return false;
        }

        if ($this->prepared) {
            return $this;
        }

        $this->prepared = true;

        $this->createFormType($formTypeArguments);
        $this->createForm($data, $formOptions);
        $this->form->handleRequest($this->request);

        return $this;
    }

    /**
     * @param mixed $data
     * @param array $formTypeArguments
     * @param array $formOptions
     *
     * @throws \Exception
     * @return bool
     */
    public function process($data = null, array $formTypeArguments = array(), array $formOptions = array())
    {
        if (null === $this->request) {
            return false;
        }

        if ($this->processed) {
            throw new \Exception('Form has already been processed');
        }

        $this->processed = true;

        $this->prepare($data, $formTypeArguments, $formOptions);

        if ($this->form->isValid()) {
            $this->onValid();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return FormView
     */
    public function getFormView()
    {
        return $this->form->createView();
    }

    public function setFormType($formType)
    {
        if (is_string($formType)) {
            $this->formTypeClass = $formType;
        } elseif (!$formType instanceof FormTypeInterface) {
            throw new \Exception('$formType should be either a class name or an instance of FormTypeInterface');
        } else {
            $this->formType = $formType;
        }
    }

    /**
     * @return mixed
     */
    abstract protected function onValid();

    /**
     * @param mixed $data
     * @param array $options
     */
    private function createForm($data, array $options)
    {
        if (null !== $this->form) {
            return;
        }

        $this->form = $this->factory->create($this->formType, $data, $options);
    }

    private function createFormType($arguments)
    {
        if (null !== $this->formType) {
            return;
        }

        if (empty($arguments)) {
            $this->formType = new $this->formTypeClass;
        } else {
            $reflection = new \ReflectionClass($this->formTypeClass);

            $this->formType = $reflection->newInstanceArgs($arguments);
        }
    }

    public function getData()
    {
        return $this->getForm()->getData();
    }
}
