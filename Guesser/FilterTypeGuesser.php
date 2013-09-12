<?php

namespace Marmelab\RestAdminBundle\Guesser;

use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Sonata\AdminBundle\Model\ModelManagerInterface;

class FilterTypeGuesser extends AbstractTypeGuesser
{
    /**
     * {@inheritdoc}
     */
    public function guessType($class, $property, ModelManagerInterface $modelManager)
    {
        if (!$ret = $this->getParentMetadataForProperty($class, $property, $modelManager)) {
            return false;
        }

        //var_dump($ret);exit();

        $options = array(
            'field_type'     => null,
            'field_options'  => array(),
            'options'        => array(),
        );

        list($metadata, $propertyName, $parentAssociationMappings) = $ret;

        $options['parent_association_mappings'] = $parentAssociationMappings;
        $options['field_name'] = $metadata->fieldMappings[$propertyName]['fieldName'];

        switch ($metadata->getTypeOfField($propertyName)) {
            case 'boolean':
                $options['field_type']    = 'sonata_type_boolean';
                $options['field_options'] = array();

                return new TypeGuess('doctrine_orm_boolean', $options, Guess::HIGH_CONFIDENCE);
            case 'datetime':
            case 'vardatetime':
            case 'datetimetz':
                return new TypeGuess('doctrine_orm_datetime', $options, Guess::HIGH_CONFIDENCE);
            case 'date':
                return new TypeGuess('doctrine_orm_date', $options, Guess::HIGH_CONFIDENCE);
            case 'decimal':
            case 'float':
                $options['field_type'] = 'number';

                return new TypeGuess('doctrine_orm_number', $options, Guess::MEDIUM_CONFIDENCE);
            case 'integer':
            case 'bigint':
            case 'smallint':
                $options['field_type'] = 'number';

                return new TypeGuess('doctrine_orm_number', $options, Guess::MEDIUM_CONFIDENCE);
            case 'string':
            case 'text':
                $options['field_type'] = 'text';

                return new TypeGuess('doctrine_orm_string', $options, Guess::MEDIUM_CONFIDENCE);
            case 'time':
                return new TypeGuess('doctrine_orm_time', $options, Guess::HIGH_CONFIDENCE);
            default:
                return new TypeGuess('doctrine_orm_string', $options, Guess::LOW_CONFIDENCE);
        }
    }
}
