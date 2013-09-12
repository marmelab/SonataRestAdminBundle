<?php

namespace Marmelab\RestAdminBundle\Datagrid;

use Marmelab\RestAdminBundle\Model\RestRepositoryInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class RestProxyQuery implements ProxyQueryInterface
{
    /** @var string */
    protected $sortBy;

    /** @var string */
    protected $sortOrder;

    /** @var int */
    protected $firstResult;

    /** @var int */
    protected $limit;

    /** @var string */
    protected $searchTerm;

    /** @var  RestRepositoryInterface */
    protected $repository;

    /**
     * @param RestRepositoryInterface $repository
     */
    public function __construct(RestRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $params = array(), $hydrationMode = null)
    {
        $page = !empty($this->limit) ? ceil(($this->firstResult + 1) / $this->limit) : 1;

        return $this->repository->findAll($page, $this->searchTerm, $this->limit, $this->sortBy, $this->sortOrder);
    }

    public function getTotalResults()
    {
        return $this->repository->countAll($this->searchTerm);
    }
    /**
     * {@inheritdoc}
     */
    public function __call($name, $args)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        return array();
    }

    public function setSearchTerm($searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    public function getSearchTerm()
    {
        return $this->searchTerm;
    }

    /**
     * {@inheritdoc}
     */
    public function setSortBy($parentAssociationMappings, $fieldName)
    {
        $this->sortBy = $fieldName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * {@inheritdoc}
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * {@inheritdoc}
     */
    public function getSingleScalarResult()
    {
        $results = $this->repository->findAll(1, null, null, $this->sortBy, $this->sortOrder);

        return is_array($results) && count($results) ? reset($results) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function __clone()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstResult($firstResult)
    {
        $this->firstResult = $firstResult;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstResult()
    {
        return $this->firstResult;
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxResults($maxResults)
    {
        $this->limit = $maxResults;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMaxResults()
    {
        return $this->limit;
    }

    /**
     * {@inheritdoc}
     */
    public function getUniqueParameterId()
    {
        return 'id';
    }

    /**
     * {@inheritdoc}
     */
    public function entityJoin(array $associationMappings)
    {
        return null;
    }
}
