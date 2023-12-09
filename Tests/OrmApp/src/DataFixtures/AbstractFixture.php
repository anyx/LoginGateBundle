<?php

namespace OrmApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractFixture extends Fixture
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @return array
     */
    abstract public function getData();

    abstract public function getReferenceCode(): string;

    abstract public function createModel(array $datum, string $code, ObjectManager $manager);

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->preLoad($manager);

        foreach ($this->getData() as $code => $datum) {
            $entity = $this->createModel($datum, $code, $manager);

            $manager->persist($this->prePersist($entity, $datum, $code, $manager));

            $this->postPersist($entity, $datum, $code, $manager);

            $this->setReference($this->getReferenceCode() . '.' . $code, $entity);

            $manager->flush();
        }

        $this->postLoad($manager);
    }

    /**
     * @param object $entity
     *
     * @return object
     */
    protected function fillEntity($entity, array $fields)
    {
        foreach ($fields as $field => $value) {
            $methodName = 'set' . ucfirst($field);
            if (is_callable([$entity, $methodName])) {
                $entity->$methodName($value);
            }
        }

        return $entity;
    }

    protected function getChildReferences(string $parentKey): array
    {
        return array_filter(
            $this->referenceRepository->getReferences(),
            function ($referenceName) use ($parentKey) {
                return 0 === strpos($referenceName, $parentKey);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    protected function prePersist($model, array $data, string $code, ObjectManager $manager)
    {
        return $model;
    }

    protected function postPersist($model, array $data, string $code, ObjectManager $manager)
    {
        return $model;
    }

    protected function preLoad(ObjectManager $manager)
    {
    }

    protected function postLoad(ObjectManager $manager)
    {
    }
}
