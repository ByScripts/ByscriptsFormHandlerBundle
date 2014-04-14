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
                $this->onValid($form, $options);

                return true;
            } else {
                $this->onInvalid($form, $options);

                return false;
            }
        }

        return false;
    }

    abstract function onValid(FormInterface $form, array $options = array());

    function onInvalid(FormInterface $form, array $options = array())
    {

    }
}
