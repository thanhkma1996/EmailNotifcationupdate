<?php
/**
 * Created by PhpStorm.
 * User: hoangnew
 * Date: 19/04/2016
 * Time: 21:29
 */
namespace Magenest\EmailNotifications\Observer\NewOrder;
use Magenest\EmailNotifications\Observer\Email\Email;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\Registry;

class NewOrder extends Email implements ObserverInterface
{

    public function execute(Observer $observer)
    {
        $orderId=$observer->getEvent()->getOrder()->getId();
        /** @var \Magento\Sales\Model\Order $orderModel */
        $orderModel = $this->_orderFactory->create();
        $order = $orderModel->load($orderId);
        $createdAt = $order->getCreatedAt();
        $couponCode = $order->getCouponCode();

            $receiverList = $this->_scopeConfig->getValue(
                    $this->neworder('rv_receive'),
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
                try {
                    $template_id = $this->_scopeConfig->getValue(
                        $this->neworder('rv_template'),
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    );

                    $transport = $this->_transportBuilder->setTemplateIdentifier($template_id)->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => $this->_storeManager->getStore()->getId(),
                        ]
                    )->setTemplateVars(
                        [
                            'orderId' => $orderModel->load($orderId)->getIncrementId(),
                            'created_at' => $createdAt,
                            'coupon_code' => $couponCode
                        ]
                    )->setFrom(
                        $this->Emailsender()
                    )->addTo(
                        'nguyendinhthanhkma@gmail.com'
                    )->getTransport();
                    $transport->sendMessage();
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->_logger->critical($e);
                }

            $receiverList = $this->_scopeConfig->getValue(
                $this->neworder('rv_order_receive'),
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
                try {
                    $template_id = $this->_scopeConfig->getValue(
                        $this->neworder('rv_order_template'),
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    );

                    $transport = $this->_transportBuilder->setTemplateIdentifier($template_id)->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => $this->_storeManager->getStore()->getId(),
                        ]
                    )->setTemplateVars(
                        [
                            'orderId' => $orderModel->load($orderId)->getIncrementId(),
                            'created_at' => $createdAt,
                        ]
                    )->setFrom(
                        $this->Emailsender()
                    )->addTo(
                        'nguyendinhthanhkma@gmail.com'
                    )->getTransport();
                    $transport->sendMessage();
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->_logger->critical($e);
                }
            }

}
