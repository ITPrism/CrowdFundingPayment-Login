<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagenavigation
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="row-fluid">
    <div class="span12">
        <h2><?php echo JText::_("COM_CROWDFUNDING_LOGIN_TITLE");?></h2>

        <form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-horizontal">

            <fieldset class="well">
                <?php foreach ($this->loginForm->getFieldset('credentials') as $field) { ?>
                    <?php if (!$field->hidden) { ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $field->label; ?>
                            </div>
                            <div class="controls">
                                <?php echo $field->input; ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-primary">
                            <?php echo JText::_('JLOGIN'); ?>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="return" value="<?php echo base64_encode($this->returnUrl); ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </fieldset>
        </form>
    </div>
</div>

<div class="row-fluid">
    <ul class="nav nav-tabs nav-stacked">
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
                <?php echo JText::_('COM_CROWDFUNDING_LOGIN_RESET'); ?></a>
        </li>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
                <?php echo JText::_('COM_CROWDFUNDING_LOGIN_REMIND'); ?></a>
        </li>
        <?php
        $usersConfig = JComponentHelper::getParams('com_users');
        if ($usersConfig->get('allowUserRegistration')) { ?>
            <li>
                <a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
                    <?php echo JText::_('COM_CROWDFUNDING_LOGIN_REGISTER'); ?></a>
            </li>
        <?php } ?>
    </ul>
</div>
