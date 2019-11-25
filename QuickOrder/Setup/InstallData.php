<?php

namespace Thesis\QuickOrder\Setup;

use Magento\Framework\DB\TransactionFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Psr\Log\LoggerInterface;
use Thesis\QuickOrder\Api\Model\Schema\StatusSchemaInterface;

class InstallData implements InstallDataInterface
{
    protected $_postFactory;

    /** @var TransactionFactory */
    private $transactionFactory;

    private $logger;

    public function __construct(\Thesis\QuickOrder\Model\StatusFactory $postFactory, TransactionFactory $transactionFactory, LoggerInterface $logger)
    {
        $this->transactionFactory = $transactionFactory;
        $this->_postFactory = $postFactory;
        $this->logger = $logger;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $transactionalModel = $this->transactionFactory->create();

        $statusData = [
            [
                StatusSchemaInterface::STATUS_CODE_COL_NAME => 'pending',
                StatusSchemaInterface::STATUS_LABEL_COL_NAME => 'Pending',
            ],
            [
                StatusSchemaInterface::STATUS_CODE_COL_NAME => 'close',
                StatusSchemaInterface::STATUS_LABEL_COL_NAME => 'Close',
            ],
            [
                StatusSchemaInterface::STATUS_CODE_COL_NAME => 'process',
                StatusSchemaInterface::STATUS_LABEL_COL_NAME => 'Process',
            ]
        ];

        for ($i = 0; $i < count($statusData); $i++) {
            $lesson = $this->_postFactory->create();
            $lesson->setLabel($statusData[$i][StatusSchemaInterface::STATUS_LABEL_COL_NAME]);
            $lesson->setStatusCode($statusData[$i][StatusSchemaInterface::STATUS_CODE_COL_NAME]);
            $transactionalModel->addObject($lesson);
        }

        try {
            $transactionalModel->save();
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}