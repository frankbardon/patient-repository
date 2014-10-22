<?php

/**
 * This file is part of the Accard package.
 *
 * (c) University of Pennsylvania
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Accard\Component\Regimen\Model;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Accard\Component\Prototype\Model\PrototypeSubjectInterface;
use Accard\Component\Field\Model\FieldSubjectInterface;

/**
 * Basic regimen interface.
 *
 * @author Frank Bardon Jr. <bardonf@upenn.edu>
 */
interface RegimenInterface extends PrototypeSubjectInterface, FieldSubjectInterface
{
    /**
     * Get regimen id.
     *
     * @return integer
     */
    public function getId();

    /**
     * Get start date.
     *
     * @return DateTime
     */
    public function getStartDate();

    /**
     * Set start date.
     *
     * @param DateTime $startDate
     * @return RegimenInterface
     */
    public function setStartDate(DateTime $startDate);

    /**
     * Get end date.
     *
     * @return DateTime|null
     */
    public function getEndDate();

    /**
     * Set end date.
     *
     * @param DateTime $endDate
     * @return RegimenInterface
     */
    public function setEndDate(DateTime $endDate = null);
}
