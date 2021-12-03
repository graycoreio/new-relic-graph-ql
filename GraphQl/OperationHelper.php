<?php

namespace Graycore\NewRelicGraphQl\GraphQl;

use Magento\Framework\GraphQl\Schema;

class OperationHelper
{

    public function getGraphQlOperation(?Schema $schema) : ?string
    {
        if (!$schema) {
            return null;
        }

        $schemaConfig = $schema->getConfig();
        if (!$schemaConfig) {
            return null;
        }

        // Mutation takes priority because the output is processed first, which will be indicated as a Query
        $hasMutationFields = count($schemaConfig->getMutation()->getFields());
        return $hasMutationFields ? $schemaConfig->getMutation()->name : $schemaConfig->getQuery()->name;
    }
}
