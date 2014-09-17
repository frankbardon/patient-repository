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

use Accard\Component\Activity\Model\Activity as BaseActivity;
use DateTime;

/**
 * Accard activity model.
 *
 * @author Frank Bardon Jr. <bardonf@upenn.edu>
 */
class Activity extends BaseActivity implements ActivityInterface
{
    // Traits
    use ActivityTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }
}
