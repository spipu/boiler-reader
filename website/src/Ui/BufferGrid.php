<?php
declare(strict_types = 1);

namespace App\Ui;

use App\Entity\Buffer;
use Spipu\UiBundle\Exception\GridException;
use Spipu\UiBundle\Service\Ui\Definition\GridDefinitionInterface;
use Spipu\UiBundle\Entity\Grid;

/**
 * Class BufferGrid
 * @SuppressWarnings(PMD.CouplingBetweenObjects)
 */
class BufferGrid implements GridDefinitionInterface
{
    /**
     * @var Grid\Grid
     */
    private $definition;

    /**
     * @return Grid\Grid
     * @throws GridException
     */
    public function getDefinition(): Grid\Grid
    {
        if (!$this->definition) {
            $this->prepareGrid();
        }

        return $this->definition;
    }

    /**
     * @return void
     * @throws GridException
     */
    private function prepareGrid(): void
    {
        $this->definition = (new Grid\Grid('buffer', Buffer::class))
            ->setPager(
                (new Grid\Pager([10, 20, 50, 100], 20))
            )
            ->addColumn(
                (new Grid\Column('id', 'app.buffer.field.id', 'id', 10))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER)))
                    ->setFilter((new Grid\ColumnFilter(true, true))->useRange())
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('time', 'app.buffer.field.time', 'time', 20))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER)))
                    ->setFilter((new Grid\ColumnFilter(true, true)))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('nb_try', 'app.buffer.field.nb_try', 'nbTry', 30))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER)))
                    ->setFilter((new Grid\ColumnFilter(true, true)))
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('created_at', 'app.buffer.field.created_at', 'createdAt', 40))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_DATETIME)))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('updated_at', 'app.buffer.field.updated_at', 'updatedAt', 50))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_DATETIME)))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
            )
            ->setDefaultSort('id', 'desc')
            ->addRowAction(
                (new Grid\Action('show', 'app.buffer.action.show', 10, 'app_buffer_show'))
                    ->setCssClass('primary')
                    ->setIcon('eye')
            )
        ;
    }
}
