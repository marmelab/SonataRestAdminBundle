<?php
namespace Marmelab\RestAdminBundle\Guesser;

use Sonata\AdminBundle\Guesser\TypeGuesserInterface;
use Marmelab\RestAdminBundle\Model\ModelManager;

class AbstractTypeGuesser implements TypeGuesserInterface
{
    /**
     * @param string                                            $baseClass
     * @param string                                            $propertyFullName
     * @param \Sonata\DoctrineORMAdminBundle\Model\ModelManager $modelManager
     *
     * @return array|null
     */
    protected function getParentMetadataForProperty($baseClass, $propertyFullName, ModelManager $modelManager)
    {
        try {
            return $modelManager->getParentMetadataForProperty($baseClass, $propertyFullName);
        } catch (\Exception $e) {
            // no metadata not found.
            return null;
        }
    }
}
