<?php
/**
 * Created by PhpStorm.
 * User: katsu
 * Date: 19/04/2016
 * Time: 14:22
 */
namespace Magenest\EmailNotifications\Observer\NewReview;

use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\Event\Observer;
use Magenest\EmailNotifications\Observer\Email\Email;
use Psr\Log\LoggerInterface;

use Magento\Framework\Registry;

class NewReview extends Email implements ObserverInterface
{

    public function execute(Observer $observer)
    {
        $reviewId = $observer->getObject()->getId();

        /** @var \Magento\Review\Model\Review $reviewModel */
        $reviewModel = $this->_reviewFactory->create();

        $detail =  $reviewModel->load($reviewId)->getDetail();
        $title = $reviewModel->load($reviewId)->getTitle();
        $productId = $reviewModel->load($reviewId)->getEntityPkValue();
        $nickname = $reviewModel->load($reviewId)->getNickname();

        $receiverList = $this->_scopeConfig->getValue(
            $this->newreview('rv_receive'),
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

                try {
                    $template_id = $this->_scopeConfig->getValue(
                        $this->newreview('rv_template'),
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    );

                    $transport = $this->_transportBuilder->setTemplateIdentifier($template_id)->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => $this->_storeManager->getStore()->getId(),
                        ]
                    )->setTemplateVars(
                        [
                            'nickname' => $nickname,
                            'productId' => $productId,
                            'store' => $this->_storeManager->getStore(),
                            'title' => $title,
                            'detail' => $detail
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