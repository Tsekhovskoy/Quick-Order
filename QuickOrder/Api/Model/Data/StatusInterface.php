<?php

namespace Thesis\QuickOrder\Api\Model\Data;

interface StatusInterface
{
    const CACHE_TAG                 = 'thesis_quickorder';

    const REGISTRY_KEY              = 'thesis_quickorder_lesson';

    const ID_FIELD                  = 'status_id';

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param string $code
     * @return StatusInterface
     */
    public function setStatusCode(string $code);

    /**
     * @return string
     */
    public function getStatusCode();
    /**
     * @param string $label
     * @return StatusInterface
     */
    public function setLabel(string $label);
    /**
     * @return string
     */
    public function getLabel();
    /**
     * @param bool $default
     * @return StatusInterface
     */
    public function setIsDefault(bool $default);
    /**
     * @return bool
     */
    public function getIsDefault();
    /**
     * @param bool $deleted
     * @return StatusInterface
     */
    public function setIsDeleted(bool $deleted);
    /**
     * @return bool
     */
    public function getIsDeleted();
}
