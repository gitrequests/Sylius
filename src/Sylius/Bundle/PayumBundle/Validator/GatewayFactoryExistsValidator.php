<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\PayumBundle\Validator;

use Sylius\Bundle\PayumBundle\Validator\Constraints\GatewayFactoryExists;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

trigger_deprecation(
    'sylius/payum-bundle',
    '1.14',
    'The "%s" class is deprecated and will be moved to the PaymentBundle in Sylius 2.0.',
    GatewayFactoryExistsValidator::class,
);
/** @deprecated since Sylius 1.14 and will be moved to the PaymentBundle in Sylius 2.0. */
final class GatewayFactoryExistsValidator extends ConstraintValidator
{
    /** @param array<string, string> $factoryNames */
    public function __construct(private array $factoryNames)
    {
    }

    /** @param string|null $value */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof GatewayFactoryExists) {
            throw new UnexpectedTypeException($constraint, GatewayFactoryExists::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (!in_array($value, array_keys($this->factoryNames), true)) {
            $this->context->buildViolation($constraint->invalidGatewayFactory)
                ->setParameter('{{ available_factories }}', implode(', ', array_keys($this->factoryNames)))
                ->addViolation()
            ;
        }
    }
}
