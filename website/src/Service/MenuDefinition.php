<?php
declare(strict_types = 1);

namespace App\Service;

use Spipu\UiBundle\Entity\Menu\Item;
use Spipu\UiBundle\Service\Menu\DefinitionInterface;

class MenuDefinition implements DefinitionInterface
{
    /**
     * @var Item
     */
    private $mainItem;

    /**
     * @return void
     */
    private function build(): void
    {
        $this->mainItem = new Item('Boiler Reader');
    }

    /**
     * @return Item
     */
    public function getDefinition(): Item
    {
        if (!$this->mainItem) {
            $this->build();
        }

        return $this->mainItem;
    }
}
