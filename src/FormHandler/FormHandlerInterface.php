<?php


namespace Byscripts\Bundle\FormHandlerBundle\FormHandler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface FormHandlerInterface
{
    function setRequest(Request $request);

    function process(FormInterface $form);

    function onValid(FormInterface $form);

    function onInvalid(FormInterface $form);
}