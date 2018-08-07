<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\EmailNotifications\Block\Adminhtml\Form\Field;

class Ordermail extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $templatesFactory
     */
    protected $templatesFactory;

    /**
     * OrderStatus constructor.
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Email\Model\ResourceModel\Template\CollectionFactory $templatesFactory,
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Email\Model\ResourceModel\Template\CollectionFactory $templatesFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->templatesFactory = $templatesFactory;
    }

    /**
     * @param $value
     * @return mixed
     */

    public function setInputName($value)
    {
        return $this->setName($value);
    }


    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            $options = $this->templatesFactory->create()->toOptionArray();
            foreach ($options as $option) {
                $this->addOption($option['value'], $option['label']);
            }
        }
        return parent::_toHtml();
    }
}
