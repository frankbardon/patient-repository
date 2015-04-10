<?php

/**
 * This file is part of the Accard package.
 *
 * (c) University of Pennsylvania
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Accard\Bundle\OutcomesBundle\Exception;

use InvalidArgumentException;

/**
 * Duplicate field exception.
 *
 * @author Frank Bardon Jr. <bardonf@upenn.edu>
 */
class DuplicateFieldException extends InvalidArgumentException implements OutcomesException
{
    /**
     * Exception constructor.
     *
     * @param string $fieldName
     */
    public function __construct($fieldName)
    {
        $this->message = sprintf('The field "%s" already exists, and may not be reset.', $fieldName);
    }
}
