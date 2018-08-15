<?php
namespace Magenest\EmailNotifications\Observer\Email;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\Registry;
use Magento\Framework\Event\ObserverInterface;

abstract class Email implements ObserverInterface
{
    CONST PATH ="";
    protected $_logger;

    protected $_coreRegistry;

    protected $_scopeConfig;

    protected $_transportBuilder;

    protected $_storeManager;

    protected $_reviewFactory;

    protected $_customerFactory;

    protected $_orderFactory;



    public function __construct(
        LoggerInterface $loggerInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Registry $registry,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory


    ) {
        $this->_logger = $loggerInterface;
        $this->_scopeConfig = $scopeConfig;
        $this->_coreRegistry = $registry;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->_reviewFactory = $reviewFactory;
        $this->_customerFactory = $customerFactory;
        $this->_orderFactory = $orderFactory;


    }


    public function transport(){
        $var =   [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $this->_storeManager->getStore()->getId(),
        ];
        return $var;
    }

    public function Emailsender(){
        $email =    $this->_scopeConfig->getValue(
            'emailnotifications_config/config_group_email_sender/config_email_sender',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE );
        return $email;
    }


    public function newreview($rv){
        switch ($rv){
            case "rv_receive":
                $path = $this::PATH.'emailnotifications_config/config_group_new_review/config_new_review_receiver';
                break;
            case  "rv_template":
                $path = $this::PATH.'emailnotifications_config/config_group_new_review/config_new_review_template';
                break;
            default;
                $path = "";
                break;
        }
        return $path;
    }


    public  function neworder($order){
        switch ($order){
            case "rv_template":
                $path = $this::PATH.'emailnotifications_config/config_group_new_coupon/config_new_coupon_template';
                break;
            case "rv_receive":
                $path = $this::PATH.'emailnotifications_config/config_group_new_coupon/config_new_coupon_receiver';
                break;
            case "rv_order_template":
                $path = $this::PATH.'emailnotifications_config/config_group_new_order/config_new_order_template';
                break;
            case 'rv_order_receive':
                $path = $this::PATH.'emailnotifications_config/config_group_new_order/config_new_order_receiver';
                break;
            default:
                $path = "";
                break;

        }
        return $path;
    }

    public function registration($reg){
        switch ($reg){
            case "rv_receive":
                $path = $this::PATH.'emailnotifications_config/config_group_new_registration/config_new_registration_receiver';
                break;
            case "rv_template":
                $path = $this::PATH.'emailnotifications_config/config_group_new_registration/config_new_registration_template';
                break;
            default:
                $path = "";
                break;

        }
        return $path;
    }

    public function newStatus($status){
        switch ($status){
            case "rv_order_from":
                $path = $this::PATH.'emailnotifications_config/config_group_new_orderstatus/config_new_orderstatus_from';
                break;
            case "rv_order_to":
                $path = $this::PATH.'emailnotifications_config/config_group_new_orderstatus/config_new_orderstatus_to';
                break;
            case "rv_order_template":
                $path = $this::PATH.'emailnotifications_config/config_group_new_orderstatus/config_new_orderstatus_template';
                break;
            case "rv_order_receive":
                $path = $this::PATH.'emailnotifications_config/config_group_new_orderstatus/config_new_orderstatus_receiver';
                break;
            default:
                $path="";

        }
        return $path;
    }

    public function subscription($sub){
        switch ($sub){
            case "rv_sub_receive":
                $path = $this::PATH.'emailnotifications_config/config_group_new_subscription/config_new_subscription_receiver';
                break;
            case "rv_sub_template":
                $path = $this::PATH.'emailnotifications_config/config_group_new_subscription/config_new_subscription_template';
                break;

            default:
                $path = "";
        }
        return $path;
    }

    public function  unsubscription($unsub){
        switch ($unsub){
            case "rv_unsub_receive":
                $path = $this::PATH.'emailnotifications_config/config_group_new_unsubscription/config_new_unsubscription_receiver';
                break;
            case "rv_unsub_template":
                $path = $this::PATH.'emailnotifications_config/config_group_new_unsubscription/config_new_unsubcription_template';
                break;
            default:
                $path = "";
        }
        return $path;
    }
    public  function wishlist($wishlist){
        switch ($wishlist){
            case 'rv_template':
                $path = $this::PATH.'emailnotifications_config/config_group_new_wishlist/config_new_wishlist_template';
                break;
            case 'rv_receive':
                $path = $this::PATH.'emailnotifications_config/config_group_new_wishlist/config_new_wishlist_receiver';
                break;
            default:
                $path = "";
                break;


        }
        return $path;
    }

}

