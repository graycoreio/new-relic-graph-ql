<?php

/**
 * Copyright © Graycore, LLC. All rights reserved.
 * See LICENSE.md for details.
 */

namespace Graycore\NewRelicGraphQl\GraphQl;

use Magento\Framework\GraphQl\Schema;

class OperationHelper
{

    /**
     * Retrieve schema operation ('Mutation' or 'Query')
     *
     * Logic isolated and slightly modified from
     * joma-webdev automatic-graphql-transaction-naming-for-new-relic repo: https://git.io/JMyAh
     *
     * @param Schema|null $schema
     * @return string|null
     */
    public function getGraphQlOperation(?Schema $schema) : ?string
    {
        if (!$schema) {
            return null;
        }

        $schemaConfig = $schema->getConfig();
        if (!$schemaConfig) { // @phpstan-ignore-line
            return null;
        }

        // Mutation takes priority because the output is processed first, which will be indicated as a Query
        $hasMutationFields = count($schemaConfig->getMutation()->getFields());
        return $hasMutationFields ? $schemaConfig->getMutation()->name : $schemaConfig->getQuery()->name;
    }
}
