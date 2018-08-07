<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\EmailNotifications\Block\Adminhtml\Form\Field;

class OrderStatus extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory
     */
    protected $statusCollectionFactory;

    /**
     * OrderStatus constructor.
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->statusCollectionFactory = $statusCollectionFactory;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            $options = $this->statusCollectionFactory->create()->toOptionArray();
            foreach ($options as $option) {
                $this->addOption($option['value'], $option['label']);
            }
        }
        return parent::_toHtml();
    }
}