<?php

namespace Magenest\EmailNotifications\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class AdditionalEmail
 */
class Status extends AbstractFieldArray
{
    /**
     * @var OrderStatus
     */
    protected $_ordermailrender;
    protected $_orderChangeFromStatusRenderer;
    protected $_orderChangeToStatusRenderer;


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
            $this->_ordermailrender->setClass('order_mail_select');
        }
        return $this->_ordermailrender;
    }


    protected function _getChangeFromStatusRenderer()
    {
        if (!$this->_orderChangeFromStatusRenderer) {
            $this->_orderChangeFromStatusRenderer = $this->getLayout()->createBlock(
                \Magenest\EmailNotifications\Block\Adminhtml\Form\Field\OrderStatus::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_orderChangeFromStatusRenderer->setClass('order_status_select');
        }
        return $this->_orderChangeFromStatusRenderer;
    }
    /**
     * @return OrderStatus|\Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getChangeToStatusRenderer()
    {
        if (!$this->_orderChangeToStatusRenderer) {
            $this->_orderChangeToStatusRenderer = $this->getLayout()->createBlock(
                \Magenest\EmailNotifications\Block\Adminhtml\Form\Field\OrderStatus::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_orderChangeToStatusRenderer->setClass('order_status_select');
        }
        return $this->_orderChangeToStatusRenderer;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    protected function _prepareToRender()
    {
        $this->addColumn('changetemplate',['label'=>__('change template'),'class'=>'required-entry','renderer'=>$this->_getMailRenderer()]);
        $this->addColumn('changefrom', ['label' => __('change from'), 'class' => 'required-entry', 'renderer' => $this->_getChangeFromStatusRenderer()]);
        $this->addColumn('changeto', ['label' => __('change to'), 'class' => 'required-entry', 'renderer' => $this->_getChangeToStatusRenderer()]);
        $this->addColumn('email',['label' => __('Email'), 'class' => 'required-entry validate-email']);
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
        $optionExtraAttr['option_'.$this->_getChangeFromStatusRenderer()->calcOptionHash($row->getData('changefrom'))] = 'selected="selected"';
        $optionExtraAttr['option_'.$this->_getChangeToStatusRenderer()->calcOptionHash($row->getData('changeto'))] = 'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }
}