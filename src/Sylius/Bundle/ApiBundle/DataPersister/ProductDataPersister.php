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

namespace Sylius\Bundle\ApiBundle\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Sylius\Bundle\ApiBundle\Exception\ProductCannotBeRemoved;
use Sylius\Component\Core\Model\ProductInterface;

trigger_deprecation(
    'sylius/api-bundle',
    '1.14',
    'The "%s" class is deprecated and will be removed in Sylius 2.0.',
    ProductDataPersister::class,
);
/** @deprecated since Sylius 1.14 and will be removed in Sylius 2.0. */
final class ProductDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private ContextAwareDataPersisterInterface $decoratedDataPersister,
    ) {
    }

    /** @param array<mixed> $context */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof ProductInterface;
    }

    /** @param array<mixed> $context */
    public function persist($data, array $context = [])
    {
        return $this->decoratedDataPersister->persist($data, $context);
    }

    /** @param array<mixed> $context */
    public function remove($data, array $context = [])
    {
        try {
            return $this->decoratedDataPersister->remove($data, $context);
        } catch (ForeignKeyConstraintViolationException) {
            throw new ProductCannotBeRemoved();
        }
    }
}
