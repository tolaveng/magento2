<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Backend
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Auth backend controller
 *
 * @category    Mage
 * @package     Mage_Backend
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Backend_Adminhtml_AuthController extends Mage_Backend_Controller_ActionAbstract
{
    /**
     * Administrator login action
     */
    public function loginAction()
    {
        $session = Mage::getSingleton('Mage_Backend_Model_Auth_Session');
        if ($session->isLoggedIn()) {
            if ($session->isFirstPageAfterLogin()) {
                $session->setIsFirstPageAfterLogin(true);
            }
            $this->_redirect(Mage::getSingleton('Mage_Backend_Model_Url')->getStartupPageUrl());
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Administrator logout action
     */
    public function logoutAction()
    {
        $auth = Mage::getSingleton('Mage_Backend_Model_Auth');
        $auth->logout();
        $auth->getAuthStorage()->addSuccess(Mage::helper('Mage_Backend_Helper_Data')->__('You have logged out.'));
        $this->getResponse()->setRedirect(Mage::helper('Mage_Backend_Helper_Data')->getHomePageUrl());
    }

    /**
     * Denied JSON action
     */
    public function deniedJsonAction()
    {
        $this->getResponse()->setBody($this->_getDeniedJson());
    }

    /**
     * Retrieve response for deniedJsonAction()
     *
     * @return string
     */
    protected function _getDeniedJson()
    {
        return Mage::helper('Mage_Core_Helper_Data')->jsonEncode(array(
            'ajaxExpired' => 1,
            'ajaxRedirect' => Mage::helper('Mage_Backend_Helper_Data')->getHomePageUrl()
        ));
    }

    /**
     * Denied IFrame action
     */
    public function deniedIframeAction()
    {
        $this->getResponse()->setBody($this->_getDeniedIframe());
    }

    /**
     * Retrieve response for deniedIframeAction()
     * @return string
     */
    protected function _getDeniedIframe()
    {
        return '<script type="text/javascript">parent.window.location = \''
            . Mage::helper('Mage_Backend_Helper_Data')->getHomePageUrl() . '\';</script>';
    }

    /**
     * Check if user has permissions to access this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return true;
    }
}
