<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Form\EventListener;

use Sudoku648\Meczyki\Image\Domain\Persistence\ImageRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormEvents;

class ImageListener implements EventSubscriberInterface
{
    private string $fieldName;

    public function __construct(
        private readonly ImageRepositoryInterface $repository,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => ['onPostSubmit', -200],
        ];
    }

    public function onPostSubmit(PostSubmitEvent $event): void
    {
        if (!$event->getForm()->isValid()) {
            return;
        }

        $data = $event->getData();

        $fieldName = $this->fieldName ?? 'image';

        if (!$event->getForm()->has($fieldName)) {
            return;
        }

        $upload = $event->getForm()->get($fieldName)->getData();

        if ($upload) {
            $image            = $this->repository->createFromUpload($upload);
            $data->$fieldName = $image;
        }
    }

    public function setFieldName(string $fieldName): self
    {
        $this->fieldName = $fieldName;

        return $this;
    }
}
