<?php
/**
 * Created by PhpStorm.
 * User: hoangnew
 * Date: 12/04/2016
 * Time: 13:58
 */
namespace Magenest\EmailNotifications\Observer\NewSubscription;

use Magenest\EmailNotifications\Observer\Email\Email;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class NewSubscription extends Email implements ObserverInterface
{

    public function execute(Observer $observer)
    {
        /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
        $subscriber = $observer->getEvent()->getSubscriber();
        $status = $subscriber->getStatus();
        $isStatusChanged =$subscriber->isStatusChanged();
        $customerId = $subscriber->getCustomerId();
        $customer = $this->_customerFactory->create()->load($customerId);
        $customerName = $customer->getName();
        $customerEmail = $customer->getEmail();

        if ($status == 1 && $isStatusChanged == true) {
            $receiverList = $this->_scopeConfig->getValue(
                $this->subscription('rv_sub_receive'),
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $receiverEmails =json_decode($receiverList,'true');
            foreach ($receiverEmails as $receiverEmail) {
                $Email = $receiverEmail['email'];
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
        }

        if ($status == 3 && $isStatusChanged == true) {
            $receiverList = $this->_scopeConfig->getValue(
                $this->unsubscription('rv_unsub_receive'),
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $receiverEmails =json_decode($receiverList);
            foreach ($receiverEmails as $receiverEmail) {
                $Email = $receiverEmail['email'];
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
}
