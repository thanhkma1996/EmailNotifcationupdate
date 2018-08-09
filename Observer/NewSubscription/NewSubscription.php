<?php
/**
 * Created by PhpStorm.
 * User: hoangnew
 * Date: 12/04/2016
 * Time: 13:58
 */
namespace Magenest\EmailNotifications\Observer\NewSubscription;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

use Magenest\EmailNotifications\Observer\Email\Email;
class NewSubscription extends Email implements ObserverInterface
{

    public function execute(Observer $observer)
    {
        /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
        $subscriber = $observer->getEvent()->getSubscriber();
        $customerId = $subscriber->getCustomerId();
        $customer = $this->_customerFactory->create()->load($customerId);
        $customerName = $customer->getName();
        $customerEmail = $customer->getEmail();

            $receiverList = $this->_scopeConfig->getValue(
                $this->subscription('rv_sub_receive'),
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $receiverEmail = json_decode($receiverList, true);
            foreach ($receiverEmail as $Emailreceiver) {
                $Email = $Emailreceiver['email'];

                try {
                    $template_id = $this->_scopeConfig->getValue(
                        $this->subscription('rv_sub_template'),
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    );
                    $transport = $this->_transportBuilder->setTemplateIdentifier($template_id)->setTemplateOptions(
                        $this->transport()
                    )->setTemplateVars(
                        [
                            'customerName' => $customerName,
                            'customerEmail' => $customerEmail,
                        ]
                    )->setFrom(
                        $this->Emailsender()
                    )->addTo(
                        $Email
                    )->getTransport();
                    $transport->sendMessage();
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->_logger->critical($e);

                }
            }

            $receiverList = $this->_scopeConfig->getValue(
                $this->unsubscription('rv_unsub_receive'),
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $receiverEmail = json_decode($receiverList, true);
            foreach ($receiverEmail as $Emailreceiver) {
                $Email = $Emailreceiver['email'];

                try {
                    $template_id = $this->_scopeConfig->getValue(
                        $this->unsubscription('rv_unsub_template'),
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    );
                    $transport = $this->_transportBuilder->setTemplateIdentifier($template_id)->setTemplateOptions(
                        $this->transport()
                    )->setTemplateVars(
                        [
                            'customerName' => $customerName,
                            'customerEmail' => $customerEmail,
                        ]
                    )->setFrom(
                        $this->Emailsender()
                    )->addTo(
                        $Email
                    )->getTransport();
                    $transport->sendMessage();
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->_logger->critical($e);
                }
            }

        }
}