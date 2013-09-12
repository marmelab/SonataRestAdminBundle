<?php

namespace Marmelab\RestAdminBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Marmelab\RestAdminBundle\Admin\FieldDescription;
use Marmelab\RestAdminBundle\Datagrid\RestProxyQuery;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\AdminBundle\Model\ModelManagerInterface;


class ModelManager implements ModelManagerInterface
{
    /**
     * @var RestRepositoryInterface
     */
    protected $repository;

    /**
     * @param RestRepositoryInterface $repository
     */
    public function setRepository(RestRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Returns the related model's metadata
     *
     * @abstract
     * @param string $class
     *
     * @return \Doctrine\ODM\PHPCR\Mapping\ClassMetadata
     */
    public function getMetadata($class)
    {
        return array();
    }

    /**
     * Returns true is the model has some metadata
     *
     * @param $class
     * @return boolean
     */
    public function hasMetadata($class)
    {
        return false;
    }

    /**
     * Returns a new FieldDescription
     *
     * @throws \RunTimeException
     * @param $class
     * @param $name
     * @param array $options
     * @return FieldDescription
     */
    public function getNewFieldDescriptionInstance($class, $name, array $options = array())
    {
        if (!is_string($name)) {
            throw new \RunTimeException('The name argument must be a string');
        }

        $fieldDescription = new FieldDescription;
        $fieldDescription->setName($name);
        $fieldDescription->setOptions($options);

        return $fieldDescription;
    }

    /**
     * @param mixed $object
     * @throws ModelManagerException
     */
    public function create($object)
    {
        try {
            $this->repository->persist($object);
        } catch (\Exception $e) {
            throw new ModelManagerException("Can't create element", 0, $e);
        }
    }

    /**
     * @param mixed $object
     * @throws ModelManagerException
     */
    public function update($object)
    {
        try {
            $this->repository->update($object);
        } catch (\Exception $e) {
            throw new ModelManagerException("Can't update object", 0, $e);
        }
    }

    /**
     * @param object $object
     * @throws ModelManagerException
     */
    public function delete($object)
    {
        try {
            $this->repository->delete($object);
        } catch (\Exception $e) {
            throw new ModelManagerException("Can't delete object", 0, $e);
        }
    }

    /**
     * Find one object from the given class repository.
     *
     * @param string $class Class name
     * @param string|int $id Identifier. Can be a string with several IDs concatenated, separated by '-'.
     * @return Object
     */
    public function find($class, $id)
    {
        try {
            $photo = $this->repository->findById($id);
        } catch (\Exception $e) {
            throw new ModelManagerException('', 0, $e);
        }

        return $photo;
    }

    /**
     * @param string $class
     * @param array  $criteria
     *
     * @return array
     * @throws \Sonata\AdminBundle\Exception\ModelManagerException
     */
    public function findBy($class, array $criteria = array())
    {
        try {
            $results = $this->repository->findAll(1, implode(' ', $criteria));
        } catch (\Exception $e) {
            throw new ModelManagerException('', 0, $e);
        }

        return $results;
    }

    /**
     * @param string $class
     * @param array  $criteria
     *
     * @return array|mixed|object
     * @throws \Sonata\AdminBundle\Exception\ModelManagerException
     */
    public function findOneBy($class, array $criteria = array())
    {
        try {
            $results = $this->repository->findAll(1, implode(' ', $criteria));
        } catch (\Exception $e) {
            throw new ModelManagerException('', 0, $e);
        }

        return count($results) ? reset($results) : array();
    }

    /**
     * @param string $parentAssociationMapping
     * @param string $class
     *
     * @return FieldDescriptionInterface
     */
    public function getParentFieldDescription($parentAssociationMapping, $class)
    {
        $fieldName = $parentAssociationMapping['fieldName'];

        $fieldDescription = $this->getNewFieldDescriptionInstance($class, $fieldName);
        $fieldDescription->setName($parentAssociationMapping);

        return $fieldDescription;
    }

    /**
     * @param string $class
     * @param string $alias
     *
     * @return mixed
     */
    public function createQuery($class, $alias = 'o', $root = null)
    {
        return new RestProxyQuery($this->repository);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function executeQuery($query)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getModelIdentifier($classname)
    {
        return $classname;
    }

    /**
     * Get the identifiers of this model class.
     *
     * This returns an array to handle cases like a primary key that is
     * composed of multiple columns. If you need a string representation,
     * use getNormalizedIdentifier resp. getUrlsafeIdentifier
     *
     * @param object $model
     *
     * @return array list of all identifiers of this model
     */
    public function getIdentifierValues($model)
    {
        return array();
    }

    /**
     * @param $class
     * @return mixed
     */
    public function getIdentifierFieldNames($class)
    {
        return array('id');
    }

    /**
     * {@inheritDoc}
     *
     * This is just taking the id out of the array again.
     */
    public function getNormalizedIdentifier($model)
    {
        if (is_scalar($model)) {
            throw new \RunTimeException('Invalid argument, object or null required');
        }

        return $model->getId();
    }

    /**
     * Get the identifiers as a string that is save to use in an url.
     *
     * This is similar to getNormalizedIdentifier but guarantees an id that can
     * be used in an URL.
     *
     * @param object $model
     *
     * @return string string representation of the id that is save to use in an url
     */
    public function getUrlsafeIdentifier($model)
    {
        return $model->getId();
    }

    /**
     * @param $class
     * @param \Sonata\AdminBundle\Datagrid\ProxyQueryInterface $queryProxy
     * @param array $idx
     * @return void
     */
    public function addIdentifiersToQuery($class, ProxyQueryInterface $queryProxy, array $idx)
    {
    }


    /**
     * @param string $class
     * @param \Sonata\AdminBundle\Datagrid\ProxyQueryInterface $queryProxy
     * @throws \Sonata\AdminBundle\Exception\ModelManagerException
     */
    public function batchDelete($class, ProxyQueryInterface $queryProxy)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getModelInstance($class)
    {
        return new $class;
    }

    /**
     * Returns the parameters used in the columns header
     *
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionInterface $fieldDescription
     * @param \Sonata\AdminBundle\Datagrid\DatagridInterface $datagrid
     * @return array
     */
    public function getSortParameters(FieldDescriptionInterface $fieldDescription, DatagridInterface $datagrid)
    {
        $values = $datagrid->getValues();

        if ($fieldDescription->getName() == $values['_sort_by']->getName()) {
            if ($values['_sort_order'] == 'ASC') {
                $values['_sort_order'] = 'DESC';
            } else {
                $values['_sort_order'] = 'ASC';
            }

            $values['_sort_by']    = $fieldDescription->getName();
        } else {
            $values['_sort_order'] = 'ASC';
            $values['_sort_by'] = $fieldDescription->getName();
        }

        return array('filter' => $values);
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridInterface $datagrid
     * @param $page
     * @return array
     */
    public function getPaginationParameters(DatagridInterface $datagrid, $page)
    {
        $values = $datagrid->getValues();

        $values['_sort_by'] = $values['_sort_by']->getName();
        $values['_page'] = $page;

        return array('filter' => $values);
    }

    /**
     * @param string $class
     * @return array
     */
    public function getDefaultSortValues($class)
    {
        return array(
            '_sort_order' => 'ASC',
            '_sort_by'    => $this->getModelIdentifier($class),
            '_page'       => 1
        );
    }

    /**
     * @param string $class
     * @param object $instance
     * @return mixed
     */
    public function modelTransform($class, $instance)
    {
        return $instance;
    }

    /**
     * @param string $class
     * @param array $array
     * @return mixed|void
     * @throws \Symfony\Component\Form\Exception\PropertyAccessDeniedException
     */
    public function modelReverseTransform($class, array $array = array())
    {
        $instance = $this->getModelInstance($class);

        return $instance;
    }

    /**
     * @param string $class
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getModelCollectionInstance($class)
    {
        return new ArrayCollection();
    }

    /**
     * @param mixed $collection
     * @return mixed
     */
    public function collectionClear(&$collection)
    {
        return $collection->clear();
    }

    /**
     * @param mixed $collection
     * @param mixed $element
     * @return mixed
     */
    public function collectionHasElement(&$collection, &$element)
    {
        return $collection->contains($element);
    }

    /**
     * @param mixed $collection
     * @param mixed $element
     * @return mixed
     */
    public function collectionAddElement(&$collection, &$element)
    {
        return $collection->add($element);
    }

    /**
     * @param mixed $collection
     * @param mixed $element
     * @return mixed
     */
    public function collectionRemoveElement(&$collection, &$element)
    {
        return $collection->removeElement($element);
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridInterface $datagrid
     * @param array $fields
     * @param null $firstResult
     * @param null $maxResult
     * @return null
     */
    public function getDataSourceIterator(DatagridInterface $datagrid, array $fields, $firstResult = null, $maxResult = null)
    {
        return null;
    }

    /**
     * @param string $class
     * @return null
     */
    public function getExportFields($class)
    {
        return null;
    }
}
