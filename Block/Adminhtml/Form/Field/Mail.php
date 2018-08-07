<?php

namespace Magenest\EmailNotifications\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class AdditionalEmail
 */
class Mail extends AbstractFieldArray
{
    protected $_ordermailrender;

    /**
     * @return OrderStatus|\Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getMailRenderer()
    {
        if (!$this->_ordermailrender) {
            $this->_ordermailrender = $this->getLayout()->createBlock(
                \Magenest\EmailNotifications\Block\Adminhtml\Form\Field\Ordermail::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_ordermailrender->setClass('order_status_select');
        }
        return $this->_ordermailrender;
    }
    protected function _prepareToRender()
    {
        $this->addColumn('changetemplate', ['label' => __('change template'),'class' => 'required-entry', 'renderer' => $this->_getMailRenderer()]);
        $this->addColumn('email',['label' => __('Email'),'class' => 'required-entry validate-email']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Email');
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $optionExtraAttr = [];
        $optionExtraAttr['option_'.$this->_getMailRenderer()->calcOptionHash($row->getData('changetemplate'))]='selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }
}