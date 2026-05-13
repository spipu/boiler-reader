<?php

declare(strict_types=1);

namespace App\WidgetSource\Data;

use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;

class DataCountPushError extends AbstractSource
{
    public function getDefinition(): Source\SourceSql
    {
        $definition = new Source\SourceSql('data-count-error', 'buffer');
        $definition->setType(self::TYPE_INT);
        $definition->setDateField(null);
        $definition->addCondition('main.nb_try > 0');

        return $definition;
    }
}
