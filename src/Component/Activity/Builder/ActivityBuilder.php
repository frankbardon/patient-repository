<?php

/**
 * This file is part of the Accard package.
 *
 * (c) University of Pennsylvania
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Accard\Component\Activity\Builder;

use DAG\Component\Resource\Builder\AbstractBuilder;
use DAG\Component\Resource\Repository\RepositoryInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Activity builder.
 *
 * Used to ease the programatic creation of activities.
 *
 * @author Frank Bardon Jr. <bardonf@upenn.edu>
 */
class ActivityBuilder extends AbstractBuilder implements ActivityBuilderInterface
{
    /**
     * Activity repository.
     *
     * @var RepositoryInterface
     */
    protected $activityRepository;

    /**
     * Field repository.
     *
     * @var RepositoryInterface
     */
    protected $fieldRepository;

    /**
     * Field value repository.
     *
     * @var RepositoryInterface
     */
    protected $fieldValueRepository;


    /**
     * Constructor.
     *
     * @param ObjectManager $manager
     * @param RepositoryInterface $activityRepository
     */
    public function __construct(
        ObjectManager $manager,
        RepositoryInterface $activityRepository,
        RepositoryInterface $fieldRepository,
        RepositoryInterface $fieldValueRepository
    ) {
        $this->manager = $manager;
        $this->activityRepository = $activityRepository;
        $this->fieldRepository = $fieldRepository;
        $this->fieldValueRepository = $fieldValueRepository;
    }

    public function getFieldRepository()
    {
        return $this->fieldRepository;
    }

    public function getFieldValueRepository()
    {
        return $this->fieldValueRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $this->resource = $this->activityRepository->createNew();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addField($name, $value, $presentation = null)
    {
        $field = $this->fieldRepository->findOneBy(array('name' => $name));

        if (null === $field) {
            $field = $this->fieldRepository->createNew();
            $field->setName($name);
            $field->setPresentation($presentation ?: $name);

            $this->manager->persist($field);
            $this->manager->flush($field);
        }

        $fieldValue = $this->fieldValueRepository->createNew();
        $fieldValue->setField($field);

        // TODO: Value is not currently supported.

        $this->resource->addField($fieldValue);

        return $this;
    }
}
