<?php


namespace Byscripts\Bundle\FormHandlerBundle\Form\Handler;

use Byscripts\Notifier\Notification\Notification;
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
     * @param Notification  $notification
     * @param array         $options
     *
     * @return bool
     */
    function process(FormInterface $form, Notification &$notification = null, array $options = array())
    {
        Notification::ensure($notification);

        if (null === $this->request) {
            return false;
        }

        $form->handleRequest($this->request);

        if($form->isSubmitted()) {
            if ($form->isValid()) {
                return $this->onValid($form, $notification, $options);
            } else {
                return $this->onInvalid($form, $notification, $options);
            }
        }

        return false;
    }

    /**
     * Triggered when the form is valid
     *
     * @param FormInterface $form
     * @param Notification  $notification
     * @param array         $options
     *
     * @return bool The value returned by the process() method
     */
    abstract function onValid(FormInterface $form, Notification $notification, array $options = array());

    /**
     * Triggered when the form is invalid
     *
     * @param FormInterface $form
     * @param Notification  $notification
     * @param array         $options
     *
     * @return bool The value returned by the process() method
     */
    function onInvalid(FormInterface $form, Notification $notification, array $options = array())
    {
        return false;
    }
}
