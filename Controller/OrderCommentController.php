<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 14/10/2014
 * Time: 14:24
 */

namespace OrderComment\Controller;


use Front\Front;
use OrderComment\Form\CommentForm;
use Propel\Runtime\Exception\PropelException;
use Thelia\Controller\BaseController;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;

class OrderCommentController extends BaseFrontController
{
    public function setComment()
    {
        $message = false;
        $commentForm = new CommentForm($this->getRequest());

        try {

            $form = $this->validateForm($commentForm);
            $data = $form->getData($form);
            $comment = $data['comment'];

            if ($comment != null) {
                $this->getRequest()->getSession()->set('order-comment', $comment);
            }

            $this->redirectToRoute("order.delivery");

        } catch (FormValidationException $e) {
            $message = Translator::getInstance()->trans("Please check your input: %s", ['%s' => $e->getMessage()], Front::MESSAGE_DOMAIN);
        } catch (PropelException $e) {
            $this->getParserContext()->setGeneralError($e->getMessage());
        } catch (\Exception $e) {
            $message = Translator::getInstance()->trans("Sorry, an error occured: %s", ['%s' => $e->getMessage()], Front::MESSAGE_DOMAIN);
        }

        if ($message !== false) {

            $commentForm->setErrorMessage($message);

            $this->getParserContext()
                ->addForm($commentForm)
                ->setGeneralError($message)
            ;

            $this->redirectToRoute("cart.view");
        }

    }
}
