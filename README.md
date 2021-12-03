# New Relic GraphQl 

## Purpose
This module intends to create [custom event data](https://docs.newrelic.com/docs/data-apis/custom-data/custom-events/apm-report-custom-events-attributes/) in new relic for every graphQl request made in Magento 2. It will create one event for each root node in the graphQl request.

## Details

This module depends on [Magento 2 - Automatic GraphQL transaction naming for New Relic](https://github.com/joma-webdevs/automatic-graphql-transaction-naming-for-new-relic) from [joma-webdevs](https://github.com/joma-webdevs) to extract query details, as a side effect it will add to the [APM transaction](https://docs.newrelic.com/docs/apm/transactions/intro-transactions/transactions-new-relic-apm/) more GraphQl request data

The event name is **GraphQlTransaction** and its data has the following structure:

```json
{
    "operation": "Mutation|Query",
    "transactionName": "/GraphQl/Controller/GraphQl\\Query\\country",
    "nodeName": "country"
}
```

## Getting Started
This module is intended to be installed with [composer](https://getcomposer.org/). From the root of your Magento 2 project:

1. Download the package
```bash
composer require graycore/new-relic-graph-ql
```
2. Enable the package

```bash
./bin/magento module:enable Graycore_NewRelicGraphQl
```
