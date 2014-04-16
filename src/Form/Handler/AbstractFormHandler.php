<?php


namespace Byscripts\Bundle\FormHandlerBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormHandler
{
    /**
     * @var Request
     */
    private $request;

    function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @param FormInterface $form
     * @param array         $options
     *
     * @return bool
     */
    function process(FormInterface $form, array $options = array())
    {
        if (null === $this->request) {
            return false;
        }

        $form->handleRequest($this->request);

        if($form->isSubmitted()) {
            if ($form->isValid()) {
                return $this->onValid($form, $options);
            } else {
                return $this->onInvalid($form, $options);
            }
        }

        return false;
    }

    /**
     * Triggered when the form is valid
     *
     * @param FormInterface $form
     * @param array         $options
     *
     * @return bool The value returned by the process() method
     */
    abstract function onValid(FormInterface $form, array $options = array());

    /**
     * Triggered when the form is invalid
     *
     * @param FormInterface $form
     * @param array         $options
     *
     * @return bool The value returned by the process() method
     */
    function onInvalid(FormInterface $form, array $options = array())
    {
        return false;
    }
}
