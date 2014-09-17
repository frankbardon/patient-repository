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

use Accard\Component\Behavior\Model\IllicitDrugBehaviorInterface as BaseIllicitDrugBehaviorInterface;

/**
 * Accard illicit drug behavior interface.
 *
 * @author Frank Bardon Jr. <bardonf@upenn.edu>
 * @author Dylan Pierce <piercedy@upenn.edu>
 */
interface IllicitDrugBehaviorInterface extends BaseIllicitDrugBehaviorInterface, BehaviorInterface
{
}
