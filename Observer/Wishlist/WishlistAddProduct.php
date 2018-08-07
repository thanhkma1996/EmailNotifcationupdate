<?php
/**
 * Created by PhpStorm.
 * User: katsu
 * Date: 19/10/2016
 * Time: 14:20
 */
namespace Magenest\EmailNotifications\Observer\Wishlist;

use Magenest\EmailNotifications\Observer\Email\Email;
use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\Event\Observer;

use Psr\Log\LoggerInterface;

use Magento\Framework\Registry;

class WishlistAddProduct extends Email implements ObserverInterface
{


    public function execute(Observer $observer)
    {
        $productName = $observer->getEvent()->getProduct()->getName();
        $customerId = $observer->getEvent()->getWishlist()->getCustomerId();
        /** @var \Magento\Customer\Model\Customer $customerModel */
        $customer = $this->_customerFactory->create()->load($customerId);
        $customerName = $customer->getName();
        $customerEmail = $customer->getEmail();

            $receiverList = $this->_scopeConfig->getValue(
                $this->wishlist('rv_receive'),
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

                try {
                    $template_id = $this->_scopeConfig->getValue(
                        $this->wishlist('rv_template'),
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    );

                    $transport = $this->_transportBuilder->setTemplateIdentifier($template_id)->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => $this->_storeManager->getStore()->getId(),
                        ]
                    )->setTemplateVars(
                        [
                            'customerName' => $customerName,
                            'customerEmail' => $customerEmail,
                            'productName' => $productName,
                            'store' => $this->_storeManager->getStore()
                        ]
                    )->setFrom(
                        $this->Emailsender()
                    )->addTo('soldiersoociu@gmail.com','thanh')->getTransport();

                    $transport->sendMessage();
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->_logger->critical($e);
                }
            }
}
