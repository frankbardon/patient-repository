<?php

/**
 * This file is part of the Accard package.
 *
 * (c) University of Pennsylvania
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Accard\Component\Sample\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Accard sample model.
 *
 * @author Frank Bardon Jr. <bardonf@upenn.edu>
 */
class Sample implements SampleInterface
{
    use \DAG\Component\Prototype\Model\PrototypeSubjectTrait;
    use \DAG\Component\Field\Model\FieldSubjectTrait;

    /**
     * Sample id.
     *
     * @var integer
     */
    protected $id;

    /**
     * Source.
     *
     * @var SourceInterface
     */
    protected $source;

    /**
     * Amount.
     *
     * @var integer
     */
    protected $amount = 0;

    /**
     * Derivatives.
     *
     * @param Collection|SourceInterface[]
     */
    protected $derivatives;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->derivatives = new ArrayCollection();
        $this->fields = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * {@inheritdoc}
     */
    public function setSource(SourceInterface $source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDerivatives()
    {
        return $this->derivatives;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDerivative(SourceInterface $derivative)
    {
        return $this->derivatives->contains($derivative);
    }

    /**
     * {@inheritdoc}
     */
    public function hasDerivatives()
    {
        return 0 < count($this->derivatives);
    }

    /**
     * {@inheritdoc}
     */
    public function addDerivative(SourceInterface $derivative)
    {
        if (!$this->hasDerivative($derivative)) {
            $this->derivatives->add($derivative);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeDerivative(SourceInterface $derivative)
    {
        if ($this->hasDerivative($derivative)) {
            $this->derivatives->removeElement($derivative);
        }

        return $this;
    }
}
