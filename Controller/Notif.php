<?php


namespace Magento\Notif\Controller;

use Magento\Framework\App\ActionInterface;

class Notif implements ActionInterface
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    protected $_pageFactory;
	
	protected $logger;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Psr\Log\LoggerInterface $logger)
    {
        $this->_pageFactory = $pageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
		$this->logger = $logger;
        return parent::__construct($context);
    }

    public function execute()
    {
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $om->get('Magento\Customer\Model\Session');
        $customerData = $customerSession->getCustomer()->getData(); //get all data of customerData
        $customerId = $customerSession->getCustomer()->getId();//get id of customer

        $order = $om->create('\Magento\Sales\Model\OrderRepository')->get($this->getOrder());

        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData(array_merge($customerData, $customerId));
		
		$this->_logger->debug(print_r($resultJson));
		
		return $resultJson;
    }

    public function getOrder(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getId();
        return $orderId;
    }
}
