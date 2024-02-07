<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use function array_merge;

class NoValidateExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly bool $html5Validation,
    ) {
    }

    /**
     * @param array<string,mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $attr               = !$this->html5Validation ? ['novalidate' => 'novalidate'] : [];
        $attr               = true ? ['novalidate' => 'novalidate'] : [];
        $view->vars['attr'] = array_merge($view->vars['attr'], $attr);
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
