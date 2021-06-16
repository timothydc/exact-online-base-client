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
    // order should be "paid" before it can be exported
    public function getOrderExportOnlyPaid(): bool;

    // item GUID for the delivery product order line
    public function getOrderExportDeliveryCostItemGuid(): string;

    // code for the warehouse which holds the product stock
    public function getProductStockWarehouseCode(): string;

    // import new product and set it as "active"
    public function getProductImportNewItemIsActive(): bool;

    // import new product and allow it to be "sellable" when out of stock
    public function getProductImportNewItemIsSellableWhenOutOfStock(): bool;

    // should we use the Sync API for product updates
    public function getProductImportUseSyncApi(): bool;

    // what is the start timestamp for the next Sync API update
    public function getProductImportSyncApiStartDatetime(): DateTimeInterface;

    // what is the interval for the Sync API
    public function getProductImportSyncApiInterval(): int;

    // should we accept Item webhooks for product updates
    public function getProductImportUseWebhookForUpdates(): bool;

    // should we accept Item webhooks for product deletes
    public function getProductImportUseWebhookForDeletes(): bool;

    // imported product should have the property "webshop" in EOL
    public function getProductImportHasPropertyWebshop(): bool;

    // imported product should have the property "stock" in EOL
    public function getProductImportHasPropertyStock(): bool;
}