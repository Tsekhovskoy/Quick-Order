<?php

namespace Thesis\QuickOrder\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Thesis\QuickOrder\Api\Model\Data\QuickOrderInterface;
use Thesis\QuickOrder\Api\Model\Data\StatusInterfaceFactory;
use Thesis\QuickOrder\Api\Model\QuickOrderRepositoryInterface;
use Thesis\QuickOrder\Controller\Adminhtml\Order as BaseAction;
use Thesis\QuickOrder\Model\QuickOrderFactory;

class Save extends BaseAction
{
    private $statusModel;

    public function __construct(
        Context $context,
        Registry $registry,
        PageFactory $pageFactory,
        QuickOrderRepositoryInterface $quickorderRepository,
        QuickOrderFactory $factory,
        LoggerInterface $logger,
        StatusInterfaceFactory $statusModel
    ) {
        $this->statusModel = $statusModel;
        parent::__construct($context, $registry, $pageFactory, $quickorderRepository, $factory, $logger);
    }

    /** {@inheritdoc} */
    public function execute()
    {
        $isPost = $this->getRequest()->isPost();

        if ($isPost) {
            $model = $this->getModel();
            $formData = $this->getRequest()->getParam('order');

            if (empty($formData)) {
                $formData = $this->getRequest()->getParams();
            }

            if (!empty($formData[QuickOrderInterface::ID_FIELD])) {
                $id = $formData[QuickOrderInterface::ID_FIELD];
                $model = $this->repository->getById($id);
            } else {
                unset($formData[QuickOrderInterface::ID_FIELD]);
            }
            /**
             * @var \Thesis\QuickOrder\Model\Status $statusModel
             */

            $statusModel = $this->statusModel->create();
            $statusModel->load("1", "is_default")->getData();

//            $this->statusFactory->create()->load($statusModel, "1", "is_default");
//            $statusModel = $this->statusCollectionFactory->create()->addFieldToFilter('is_default', ['eq' => 1])->getFirstItem();

            $model->setData($formData);
            $model->setStatus($statusModel);

            try {
                $model = $this->repository->save($model);
                $this->messageManager->addSuccessMessage(__('Order has been saved.'));
                if ($this->getRequest()->getParam('back')) {
                    return $this->_redirect('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }

                return $this->redirectToGrid();
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $this->messageManager->addErrorMessage(__('Order doesn\'t save'));
            }

            $this->_getSession()->setFormData($formData);

            return (!empty($model->getId())) ?
                $this->_redirect('*/*/edit', ['id' => $model->getId()])
                : $this->_redirect('*/*/create');
        }

        return $this->doRefererRedirect();
    }
}
