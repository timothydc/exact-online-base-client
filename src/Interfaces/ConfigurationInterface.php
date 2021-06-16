<?php

declare(strict_types=1);

namespace PolarisDC\ExactOnline\BaseClient\Interfaces;

/*
 * Method naming suggestion:
 * "get"
 * + resource (product | order | customer | ...)
 * + action (import | export | ...)
 * + unique something
 *
 */

use DateTimeInterface;

interface ConfigurationInterface
{
    // order
    public function getOrderExportOnlyPaid(): bool;

    public function getOrderExportDeliveryCostItemGuid(): string;

    // product
    public function getProductStockWarehouseCode(): string;

    public function getProductImportNewItemIsActive(): bool;

    public function getProductImportNewItemIsSellableWhenOutOfStock(): bool;

    public function getProductImportUseSyncApi(): bool;

    public function getProductImportSyncApiStartDatetime(): DateTimeInterface;

    public function getProductImportSyncApiInterval(): int;

    public function getProductImportUseWebhook(): bool;
}