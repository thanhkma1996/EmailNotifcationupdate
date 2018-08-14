<?php
/**
 * Created by PhpStorm.
 * User: hoangnew
 * Date: 18/04/2016
 * Time: 11:38
 */
namespace Magenest\EmailNotifications\Observer\NewStatus;

use Magenest\EmailNotifications\Observer\Email\Email;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class OrderSaveAfter extends Email implements ObserverInterface
{

    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        $statusBefore = $order->getOrigData('status');
        $statusAfter = $order->getStatus();



        if (($statusBefore !== false) && ($statusAfter !== false)) {
            $receiverList = $this->_scopeConfig->getValue(
                $this->newStatus('rv_order_receive'),
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $receiverEmail = json_decode($receiverList, true);
            foreach ($receiverEmail as $Emailreceiver) {
                $Email = $Emailreceiver['email'];
                $template_id = $this->_scopeConfig->getValue(
                    $this->newStatus('rv_order_template'),
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );
                $transport = $this->_transportBuilder->setTemplateIdentifier($template_id)->setTemplateOptions(
                    $this->transport()
                )->setTemplateVars(
                    [
                        'orderId' => $order->getIncrementId(),
                        'updated_at' => $order->getUpdatedAt(),
                        'statebefore' => $statusBefore,
                        'stateafter' => $statusAfter
                    ]
                )->setFrom(
                    $this->Emailsender()
                )->addTo(
                    $Email
                )->getTransport();
                $transport->sendMessage();
            }
        }
    }

}
