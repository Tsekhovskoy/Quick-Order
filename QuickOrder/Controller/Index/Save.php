<?php
namespace Thesis\QuickOrder\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Thesis\QuickOrder\Api\Model\Data\QuickOrderInterfaceFactory;
use Thesis\QuickOrder\Api\Model\Data\StatusInterfaceFactory;
use Thesis\QuickOrder\Api\Model\QuickOrderRepositoryInterface;
use Thesis\QuickOrder\Model\ResourceModel\Status\CollectionFactory;
use Thesis\QuickOrder\Model\ResourceModel\StatusFactory;
use Thesis\QuickOrder\Model\Status;

class Save extends Action
{
    /**
     * @var QuickOrderRepositoryInterface
     */
    protected $repository;
    /**
     * @var QuickOrderInterfaceFactory
     */
    protected $modelFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var StatusInterfaceFactory
     */
    private $statusModel;
    /**
     * @var StatusFactory
     */
    protected $statusFactory;
    /**
     * @var Collection
     */
    protected $statusCollectionFactory;
    /**
     * Save constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param CollectionFactory $statusCollectionFactory
     * @param StatusInterface $statusModel
     * @param StatusFactory $statusFactory
     * @param QuickOrderRepositoryInterface $repository
     * @param QuickOrderInterfaceFactory $quickOrderInterfaceFactory
     * @param LoggerInterface $logger
     */
    public function __construct(Context $context, PageFactory $resultPageFactory, CollectionFactory $statusCollectionFactory, StatusInterfaceFactory $statusModel, StatusFactory $statusFactory, QuickOrderRepositoryInterface $repository, QuickOrderInterfaceFactory $quickOrderInterfaceFactory, LoggerInterface $logger)
    {
        $this->statusModel   =  $statusModel;
        $this->statusFactory = $statusFactory;
        $this->statusCollectionFactory = $statusCollectionFactory;
        $this->repository    =  $repository;
        $this->modelFactory  =  $quickOrderInterfaceFactory;
        $this->logger        =  $logger;
        parent::__construct($context);
    }
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        /**
         * @var Status $statusmodel
         * @var AbstractModel $model
         */

        $statusmodell = $this->statusModel->create();
        $this->statusFactory->create()->load($statusmodell,"1","is_default");



        $statusmodel = $this->statusCollectionFactory->create()->addFieldToFilter('is_default', ['eq' => 1])->getFirstItem();
//      $statusCollection = $this->statusCollectionFactory->create()->getItems();

        $model = $this->modelFactory->create();
        $model->setStatus($statusmodell);
        $model->setName($params['name']);
        $model->setSku($params['sku']);
        $model->setPhone($params['phone']);
        $model->setEmail($params['email']);
        try {
            $this->repository->save($model);
            $this->messageManager->addSuccessMessage('Saved!');
        } catch (CouldNotSaveException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage('Error');
        }
        $this->_redirect($params['url']);
    }
}
