<?php
/*
 * This file is part of the ByscriptsFormHandlerBundle package.
 *
 * (c) Thierry Goettelmann <thierry@byscripts.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Byscripts\Bundle\FormHandlerBundle\FormHandler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
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
     * @param null  $data
     * @param array $options
     *
     * @return bool
     */
    public function process($data = null, array $options = array())
    {
        if (null === $this->request) {
            return false;
        }

        $this->createForm($data, $options);

        $this->form->handleRequest($this->request);

        if ($this->form->isValid()) {
            $this->onValid();

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    abstract protected function getFormType();

    /**
     * @return mixed
     */
    abstract protected function onValid();

    /**
     * @param       $data
     * @param array $options
     */
    private function createForm($data, array $options)
    {
        $this->form = $this->factory->create($this->getFormType(), $data, $options);
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
}
