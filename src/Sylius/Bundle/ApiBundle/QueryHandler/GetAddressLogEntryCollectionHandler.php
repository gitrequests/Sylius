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

namespace Sylius\Bundle\ApiBundle\QueryHandler;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Bundle\ApiBundle\Query\GetAddressLogEntryCollection;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceLogEntryRepositoryInterface;
use Sylius\Component\Addressing\Model\AddressLogEntry;

trigger_deprecation(
    'sylius/api-bundle',
    '1.14',
    'The "%s" class is deprecated and will be removed in Sylius 2.0.',
    GetAddressLogEntryCollectionHandler::class,
);

/** @deprecated since Sylius 1.14 and will be removed in Sylius 2.0. */
final class GetAddressLogEntryCollectionHandler
{
    public function __construct(
        private ResourceLogEntryRepositoryInterface $addressLogEntryRepository,
    ) {
    }

    /** @return Collection<array-key, AddressLogEntry> */
    public function __invoke(GetAddressLogEntryCollection $query): Collection
    {
        $queryBuilder = $this->addressLogEntryRepository->createByObjectIdQueryBuilder((string) $query->getAddressId());

        return new ArrayCollection($queryBuilder->getQuery()->getResult());
    }
}
