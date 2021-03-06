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

use Accard\Component\Behavior\Model\BehaviorInterface as BaseBehaviorInterface;
use DAG\Component\Resource\Model\BlameableInterface;
use DAG\Component\Resource\Model\VersionableInterface;
use DAG\Component\Resource\Model\TimestampableInterface;

/**
 * Accard activity interface.
 *
 * @author Frank Bardon Jr. <bardonf@upenn.edu>
 */
interface BehaviorInterface extends
    BaseBehaviorInterface,
    TimestampableInterface,
    BlameableInterface,
    VersionableInterface
{
    /**
     * Get patient.
     *
     * @return PatientInterface
     */
    public function getPatient();

    /**
     * Set patient.
     *
     * @param PatientInterface $patient
     * @return BehaviorInterface
     */
    public function setPatient(PatientInterface $patient = null);

    /**
     * Test for presence of patient.
     *
     * @return boolean
     */
    public function hasPatient();
}
