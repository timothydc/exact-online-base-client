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

interface ConfigurationInterface
{
    // order should be exported automatically
    public function getOrderExportAutomatically(): bool;

    // order should be "paid" before it can be exported
    public function getOrderExportOnlyPaid(): bool;

    // item GUID for the delivery product order line
    public function getOrderExportDeliveryCostItemGuid(): string;

    // item GUID for the free delivery product order line
    public function getOrderExportFreeDeliveryCostItemGuid(): string;

    // choose a field to use as a search field in the Exact Account
    public function getOrderCustomerSearchField(): string;

    // allow updating of existing customer addresses in Exact during order export
    public function getOrderAllowUpdatingOfAddressesInExact(): bool;

    // allow imported order to update order status
    public function getOrderAllowImportToUpdateOrderState(): bool;

    // allow imported order to update delivery status
    public function getOrderAllowImportToUpdateDeliveryState(): bool;

    // force product stock recalculation when an order is completed
    public function shouldOrderProductStockBeRecalculatedAfterOrderCompletion(): bool;

    // code for the warehouse which holds the product stock
    public function getProductStockWarehouseCode(): string;

    // import new product and set it as "active"
    public function getProductImportNewItemIsActive(): bool;

    // import new product and allow it to be "sellable" when out of stock
    public function getProductImportNewItemIsSellableWhenOutOfStock(): bool;

    // should we use the Sync API for product updates
    public function getProductImportUseSyncApi(): bool;

    // should we use the Sync API for stock updates
    public function getStockImportUseSyncApi(): bool;

    // should we accept Item webhooks for product updates
    public function getProductImportUseWebhookForUpdates(): bool;

    // should we accept Item webhooks for product deletes
    public function getProductImportUseWebhookForDeletes(): bool;

    // should we accept Stock Position webhooks
    public function getStockImportUseWebhookForUpdates(): bool;

    // should we accept Order webhooks
    public function getOrderUseWebhookForUpdates(): bool;

    // should we accept Account webhooks
    public function getCustomerUseWebhookForUpdates(): bool;

    // imported product should have the property "webshop" in EOL
    public function getProductImportHasPropertyWebshop(): bool;

    // imported product should have the property "stock" in EOL
    public function getProductImportHasPropertyStock(): bool;

    // imported product wants to import stock data
    public function getProductHasImportStock(): bool;

    // imported product wants to import price data
    public function getProductHasImportPrice(): bool;

    // imported product wants to import other information
    public function getProductHasImportInformation(): bool;

    // imported product are allowed to insert new products into Shopware
    public function getProductIsAllowedToInsertNew(): bool;

    // products have a product/variant relation
    public function getHasProductVariantConfiguration(): bool;

    // imported customers are allowed to insert new customers into Shopware
    public function getCustomerIsAllowedToInsertNew(): bool;

    // import customers with this customer groupd
    public function getCustomerImportDefaultGroup(): string;

    // prefetch selected customer classifications
    public function getActiveCustomerClassifications(): array;

    // should we use the Sync API for customer
    public function getCustomerImportUseSyncApi(): bool;

    // import customer default billing address type from EOL
    public function getCustomerImportDefaultAddressType(): int;
}
