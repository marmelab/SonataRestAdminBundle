<?php

namespace Marmelab\RestAdminBundle\Model;

interface RestRepositoryInterface
{
    /**
     * Returns a entity by its id
     *
     * @param int $id
     *
     * @return mixed|null
     */
    public function findById($id);

    /**
     * @param int    $page
     * @param string $q
     * @param int    $limit
     * @param string $sort
     * @param string $sortdirection
     *
     * @return mixed|null
     */
    public function findAll($page = 1, $q = null, $limit = null, $sort = null, $sortdirection = null);

    /**
     * @param string $q
     *
     * @return int
     */
    public function countAll($q = null);

    /**
     * Store a new AbstractEntity
     *
     * @param mixed $entity
     *
     * @return mixed
     * @throws \LogicException
     */
    public function persist($entity);

    /**
     * Update a AbstractEntity
     *
     * @param mixed $entity
     *
     * @return mixed
     * @throws \LogicException
     */
    public function update($entity);

    /**
     * Delete a Photo
     *
     * @param mixed $entity
     *
     * @return mixed
     * @throws \LogicException
     */
    public function delete($entity);
}
