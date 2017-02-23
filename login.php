<?php
/**
 * @package      CrowdfundingPayment
 * @subpackage   Plugins
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2017 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;

// no direct access
defined('_JEXEC') or die;

/**
 * CrowdfundingPayment - Login Plug-in displays a login form on step 2 of the payment wizard.
 *
 * @package      CrowdfundingPayment
 * @subpackage   Plugins
 */
class plgCrowdfundingPaymentLogin extends JPlugin
{
    protected $autoloadLanguage = true;

    /**
     * @var JApplicationSite
     */
    protected $app;

    protected $loginForm;
    protected $returnUrl;

    protected $rewardId;
    protected $amount;
    protected $terms;

    /**
     * This method prepares a payment gateway - buttons, forms,...
     * That gateway will be displayed on the summary page as a payment option.
     *
     * @param string    $context This string gives information about that where it has been executed the trigger.
     * @param stdClass  $item    A project data.
     * @param stdClass  $nextStepParams
     * @param Joomla\Registry\Registry $params  The parameters of the component
     *
     * @throws EnvironmentIsBrokenException
     * @return null|string
     */
    public function onPreparePaymentStep($context, $item, $nextStepParams, $params)
    {
        if (strcmp('com_crowdfunding.payment.step.login', $context) !== 0) {
            return null;
        }

        if ($this->app->isAdmin()) {
            return null;
        }

        $doc = JFactory::getDocument();
        /**  @var $doc JDocumentHtml */

        // Check document type
        $docType = $doc->getType();
        if (strcmp('html', $docType) !== 0) {
            return null;
        }

        // Get user ID.
        $userId = JFactory::getUser()->get('id');

        // Display login form
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');

        $form = JForm::getInstance('com_users.login', 'login', array('load_data' => false), false, false);

        $this->loginForm = $form;

        $paymentSessionContext = Crowdfunding\Constants::PAYMENT_SESSION_CONTEXT.$item->id;
        $paymentSessionLocal   = $this->app->getUserState($paymentSessionContext);

        $terms    = (int)$paymentSessionLocal->terms;
        $rewardId = (int)$paymentSessionLocal->rewardId;
        $amount   = $paymentSessionLocal->amount;

        // Generate token that will be accessed by the payment.process.
        $token           = hash('sha256', $this->app->get('secret'));
        $this->returnUrl = 'index.php?option=com_crowdfunding&task=backing.process&id=' . $item->id . '&rid=' . $rewardId . '&amount=' . rawurlencode($amount) .'&terms='.$terms.'&token=' . $token;

        // Point to next step, if it is available for registered users.
        // Otherwise, point to first step.
        if ($nextStepParams->show_to_registered) {
            $this->returnUrl .= '&layout=' . $nextStepParams->layout;
        }

        // Get the path for the layout file
        $path = JPluginHelper::getLayoutPath('crowdfundingpayment', 'login');

        // Render the login form.
        ob_start();
        include $path;
        $html = ob_get_clean();

        return $html;
    }

    /**
     * Return information about a step on the payment wizard.
     *
     * @param string $context
     *
     * @return null|array
     */
    public function onPrepareWizardSteps($context)
    {
        if (strcmp('com_crowdfunding.payment.wizard', $context) !== 0) {
            return null;
        }

        if ($this->app->isAdmin()) {
            return null;
        }

        $doc = JFactory::getDocument();
        /**  @var $doc JDocumentHtml */

        // Check document type
        $docType = $doc->getType();
        if (strcmp('html', $docType) !== 0) {
            return null;
        }

        $userId = (int)JFactory::getUser()->get('id');
        if ($userId > 0) {
            return null;
        }

        return array(
            'title'   => JText::_('PLG_CROWDFUNDINGPAYMENT_LOGIN_STEP_TITLE'),
            'context' => 'login',
            'allowed' => Prism\Constants::YES
        );
    }
}
