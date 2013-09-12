<?php

namespace Marmelab\RestAdminBundle\Builder;

use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;
use Sonata\AdminBundle\Builder\ShowBuilderInterface;
use Sonata\AdminBundle\Guesser\TypeGuesserInterface;

class ShowBuilder implements ShowBuilderInterface
{
    protected $guesser;

    protected $templates;

    /**
     * @param \Sonata\AdminBundle\Guesser\TypeGuesserInterface $guesser
     * @param array                                            $templates
     */
    public function __construct(TypeGuesserInterface $guesser, array $templates)
    {
        $this->guesser   = $guesser;
        $this->templates = $templates;
    }

    /**
     * @param array $options
     *
     * @return \Sonata\AdminBundle\Admin\FieldDescriptionCollection
     */
    public function getBaseList(array $options = array())
    {
        return new FieldDescriptionCollection;
    }

    /**
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionCollection $list
     * @param null                                                 $type
     * @param \Sonata\AdminBundle\Admin\FieldDescriptionInterface  $fieldDescription
     * @param \Sonata\AdminBundle\Admin\AdminInterface             $admin
     *
     * @return mixed
     */
    public function addField(FieldDescriptionCollection $list, $type = null, FieldDescriptionInterface $fieldDescription, AdminInterface $admin)
    {
        if ($type == null) {
            $guessType = $this->guesser->guessType($admin->getClass(), $fieldDescription->getName(), $admin->getModelManager());
            $fieldDescription->setType($guessType->getType());
        } else {
            $fieldDescription->setType($type);
        }


        $admin->addShowFieldDescription($fieldDescription->getName(), $fieldDescription);

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
}
