<?php


namespace Byscripts\Bundle\Form\HandlerBundle\FormHandler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormHandler implements FormHandlerInterface
{
    /**
     * @var Request
     */
    private $request;

    function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    function process(FormInterface $form)
    {
        if (null === $this->request) {
            return false;
        }

        $form->handleRequest($this->request);

        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->onValid($form);

                return true;
            } else {
                $this->onInvalid($form);

                return false;
            }
        }

        return false;
    }
}
