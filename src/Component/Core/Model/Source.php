<?php

/**
 * This file is part of the Accard package.
 *
 * (c) University of Pennsylvania
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Accard\Component\Core\Model;

use Accard\Component\Sample\Model\Source as BaseSource;

/**
 * Accard source model.
 *
 * @author Frank Bardon Jr. <bardonf@upenn.edu>
 */
class Source extends BaseSource implements SourceInterface
{
    /**
     * Patient.
     *
     * @var PatientInterface
     */
    protected $patient;


    /**
     * {@inheritdoc}
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * {@inheritdoc}
     */
    public function setPatient(PatientInterface $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPatient()
    {
        return $this->patient instanceof PatientInterface;
    }
}
