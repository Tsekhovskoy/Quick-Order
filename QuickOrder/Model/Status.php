<?php

namespace Thesis\QuickOrder\Model;

use Magento\Framework\Model\AbstractModel;
use Thesis\QuickOrder\Api\Model\Data\StatusInterface as InterfaceStatus;
use Thesis\QuickOrder\Api\Model\Schema\StatusSchemaInterface;
use Thesis\QuickOrder\Model\ResourceModel\Status as ResourceModel;

class Status extends AbstractModel implements InterfaceStatus
{
    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function getId()
    {
        $this->getData(StatusSchemaInterface::STATUS_ID_COL_NAME);
    }

    public function setStatusCode(string $code): InterfaceStatus
    {
        $this->setData(StatusSchemaInterface::STATUS_CODE_COL_NAME, $code);

        return $this;
    }

    public function getStatusCode(): string
    {
        return $this->getData(StatusSchemaInterface::STATUS_CODE_COL_NAME);
    }

    public function setLabel(string $label): InterfaceStatus
    {
        $this->setData(StatusSchemaInterface::STATUS_LABEL_COL_NAME, $label);

        return $this;
    }

    public function getLabel(): string
    {
        return $this->getData(StatusSchemaInterface::STATUS_LABEL_COL_NAME);
    }

    public function setIsDefault(bool $default): InterfaceStatus
    {
        $this->setData(StatusSchemaInterface::IS_DEFAULT, (int) $default);

        return $this;
    }

    public function getIsDefault(): bool
    {
        return (bool)$this->getData(StatusSchemaInterface::IS_DEFAULT);
    }

    public function setIsDeleted(bool $deleted): InterfaceStatus
    {
        $this->setData(StatusSchemaInterface::IS_DELETED, (int) $deleted);

        return $this;
    }

    public function getIsDeleted(): bool
    {
        return (bool)$this->getData(StatusSchemaInterface::IS_DELETED);
    }
}
