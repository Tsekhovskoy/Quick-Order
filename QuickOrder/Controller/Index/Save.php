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
    private $status;
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
     * @param StatusFactory $statusFactory
     * @param QuickOrderRepositoryInterface $repository
     * @param QuickOrderInterfaceFactory $quickOrderInterfaceFactory
     * @param LoggerInterface $logger
     * @param StatusInterface $status
     */
    public function __construct(Context $context, PageFactory $resultPageFactory, CollectionFactory $statusCollectionFactory, StatusFactory $statusFactory, QuickOrderRepositoryInterface $repository, QuickOrderInterfaceFactory $quickOrderInterfaceFactory, LoggerInterface $logger, StatusInterfaceFactory $status)
    {
        $this->statusFactory = $statusFactory;
        $this->statusCollectionFactory = $statusCollectionFactory;
        $this->repository    =  $repository;
        $this->modelFactory  =  $quickOrderInterfaceFactory;
        $this->logger        =  $logger;
        $this->status        =  $status;
        return parent::__construct($context);
    }
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        /** @var AbstractModel $model */

        $statusmodel = $this->status->create();
        $statusResource = $this->statusFactory->create();
        $statusCollection = $this->statusCollectionFactory->create();
        $model = $this->modelFactory->create();
        $model->setStatus($statusmodel);
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
