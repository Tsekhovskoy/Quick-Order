<?php

namespace Thesis\QuickOrder\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Thesis\QuickOrder\Api\Model\Data\QuickOrderInterface;
use Thesis\QuickOrder\Api\Model\Data\StatusInterface;
use Thesis\QuickOrder\Api\Model\Schema\QuickOrderSchemaInterface;
use Thesis\QuickOrder\Model\ResourceModel\QuickOrder as ResourceModel;

class QuickOrder extends AbstractModel implements QuickOrderInterface
{
    private $statusRepository;

    /**
     * @var StatusInterface
     */
    private $status;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    public function __construct(
        Context $context,
        Registry $registry,
        TimezoneInterface $timezone,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->timezone = $timezone;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function setName(string $name): QuickOrderInterface
    {
        $this->setData(QuickOrderSchemaInterface::NAME_COL_NAME, $name);

        return $this;
    }

    public function getName(): string
    {
        return $this->getData(QuickOrderSchemaInterface::NAME_COL_NAME);
    }

    public function setSku(string $sku): QuickOrderInterface
    {
        $this->setData(QuickOrderSchemaInterface::SKU_COL_NAME, $sku);

        return $this;
    }

    public function getSku(): string
    {
        return $this->getData(QuickOrderSchemaInterface::SKU_COL_NAME);
    }

    public function setPhone(string $phone): QuickOrderInterface
    {
        $this->setData(QuickOrderSchemaInterface::PHONE_COL_NAME, $phone);

        return $this;
    }

    public function getPhone(): string
    {
        return $this->getData(QuickOrderSchemaInterface::PHONE_COL_NAME);
    }

    public function setEmail(string $email): QuickOrderInterface
    {
        $this->setData(QuickOrderSchemaInterface::EMAIL_COL_NAME, $email);

        return $this;
    }

    public function getEmail(): string
    {
        return $this->getData(QuickOrderSchemaInterface::EMAIL_COL_NAME);
    }

    public function setStatus(StatusInterface $status): QuickOrderInterface
    {
        if (null === $status->getId()) {
            throw new LocalizedException(__('Status not created'));
        }

        $this->setData(QuickOrderSchemaInterface::STATUS_COL_NAME, $status->getId());

        return $this;
    }

    public function getStatus(): StatusInterface
    {
        $statusId =  $this->getData(QuickOrderSchemaInterface::STATUS_COL_NAME);

        if (null === $this->status) {
            // @TODO load status by ID
        }

        return $this->status;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        $timestamp = $this->getData(QuickOrderSchemaInterface::CREATED_AT_COL_NAME);

        return $this->timezone->date($timestamp);
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        $timestamp = $this->getData(QuickOrderSchemaInterface::UPDATED_AT_COL_NAME);

        return $this->timezone->date($timestamp);
    }
}
