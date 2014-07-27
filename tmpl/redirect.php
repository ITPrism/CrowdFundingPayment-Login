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
        <h2><?php echo JText::_("PLG_CROWDFUNDINGPAYMENT_LOGIN_TITLE");?></h2>

        <p class="alert">
            <i class="icon-info-sign"></i>
            <?php echo JText::_("PLG_CROWDFUNDINGPAYMENT_LOGIN_YOU_ARE_SIGNED_NOT_REDIRECTED");?>
        </p>
        <form action="<?php echo JRoute::_("index.php?option=com_crowdfunding&task=backing.process"); ?>" method="post">
            <input type="hidden" name="id" value="<?php echo (int)$item->id; ?>" />
            <input type="hidden" name="rid" value="<?php echo (int)$this->rewardId; ?>" />
            <input type="hidden" name="amount" value="<?php echo (float)$this->amount; ?>" />

            <?php echo JHtml::_('form.token'); ?>

            <button type="submit" class="btn btn-primary">
                <?php echo JText::_("PLG_CROWDFUNDINGPAYMENT_LOGIN_NEXT_STEP");?>
            </button>
        </form>
    </div>
</div>

