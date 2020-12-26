<?php
declare(strict_types = 1);

namespace App\Ui;

use App\Entity\Buffer;
use Spipu\UiBundle\Entity\EntityInterface;
use Spipu\UiBundle\Entity\Form\Field;
use Spipu\UiBundle\Entity\Form\FieldSet;
use Spipu\UiBundle\Entity\Form\Form;
use Spipu\UiBundle\Exception\FormException;
use Spipu\UiBundle\Service\Ui\Definition\EntityDefinitionInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type;

class BufferForm implements EntityDefinitionInterface
{
    /**
     * @var Form
     */
    protected $definition;

    /**
     * @return Form
     * @throws FormException
     */
    public function getDefinition(): Form
    {
        if (!$this->definition) {
            $this->prepareForm();
        }

        return $this->definition;
    }

    /**
     * @return void
     * @throws FormException
     * @SuppressWarnings(PMD.ExcessiveMethodLength)
     */
    protected function prepareForm(): void
    {
        $this->definition = new Form('user_admin', Buffer::class);

        $this->definition
            ->addFieldSet(
                (new FieldSet('information', 'app.buffer.fieldset.information', 10))
                    ->useHiddenInForm()
                    ->setCssClass('col-xs-12 col-md-6')
                    ->addField(new Field(
                        'id',
                        Type\IntegerType::class,
                        10,
                        ['label'    => 'app.buffer.field.id']
                    ))
                    ->addField(new Field(
                        'time',
                        Type\IntegerType::class,
                        20,
                        ['label'    => 'app.buffer.field.time']
                    ))
                    ->addField(new Field(
                        'nbTry',
                        Type\IntegerType::class,
                        30,
                        ['label'    => 'app.buffer.field.time']
                    ))
                    ->addField(new Field(
                        'createdAt',
                        Type\DateTimeType::class,
                        40,
                        ['label'    => 'app.buffer.field.created_at']
                    ))
                    ->addField(new Field(
                        'updatedAt',
                        Type\DateTimeType::class,
                        50,
                        ['label'    => 'app.buffer.field.updated_at']
                    ))
            )
            ->addFieldSet(
                (new FieldSet('others', 'app.buffer.fieldset.data', 20))
                    ->useHiddenInForm()
                    ->setCssClass('col-xs-12 col-md-6')
                    ->addField(
                        (new Field(
                            'dataAsArray',
                            Type\TextType::class,
                            50,
                            ['label'    => 'app.buffer.field.data']
                        ))->setTemplateView('view/data.html.twig')
                    )
            )
        ;
    }

    /**
     * @param FormInterface $form
     * @param EntityInterface|null $resource
     * @return void
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function setSpecificFields(FormInterface $form, EntityInterface $resource = null): void
    {
    }
}
