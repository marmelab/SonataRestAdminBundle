<?php

namespace Marmelab\RestAdminBundle\Builder;

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;
use Sonata\AdminBundle\Builder\ListBuilderInterface;
use Sonata\AdminBundle\Guesser\TypeGuesserInterface;

class ListBuilder implements ListBuilderInterface
{
    protected $guesser;

    protected $templates = array();

    /**
     * @param \Sonata\AdminBundle\Guesser\TypeGuesserInterface $guesser
     * @param array                                            $templates
     */
    public function __construct(TypeGuesserInterface $guesser, $templates = array())
    {
        $this->guesser   = $guesser;
        $this->templates = $templates;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseList(array $options = array())
    {
        return new FieldDescriptionCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function buildField($type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin)
    {
        if ($type == null) {
            $guessType = $this->guesser->guessType($admin->getClass(), $fieldDescription->getName(), $admin->getModelManager());
            $fieldDescription->setType($guessType->getType());
        } else {
            $fieldDescription->setType($type);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addField(FieldDescriptionCollection $list, $type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin)
    {
        $this->buildField($type, $fieldDescription, $admin);
        $admin->addListFieldDescription($fieldDescription->getName(), $fieldDescription);

        $list->add($fieldDescription);
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getTemplate($type)
    {
        if (!isset($this->templates[$type])) {
            return null;
        }

        return $this->templates[$type];
    }

    /**
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionInterface $fieldDescription
     *
     * @return \Sonata\AdminBundle\Admin\FieldDescriptionInterface
     */
    public function buildActionFieldDescription(FieldDescriptionInterface $fieldDescription)
    {
        return $fieldDescription;
    }
}
