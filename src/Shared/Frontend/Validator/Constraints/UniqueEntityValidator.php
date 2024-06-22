<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function in_array;
use function is_int;
use function is_object;
use function is_string;
use function mb_strtolower;

/**
 * @deprecated
 */
final class UniqueEntityValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!is_object($value)) {
            throw new UnexpectedTypeException($value, 'object');
        }

        if (!$constraint instanceof UniqueEntity) {
            throw new UnexpectedTypeException($constraint, UniqueEntity::class);
        }

        $qb = $this->entityManager->createQueryBuilder()
            ->select('COUNT(e)')
            ->from($constraint->entityClass, 'e');

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ((array) $constraint->fields as $dtoField => $entityField) {
            if (is_int($dtoField)) {
                $dtoField = $entityField;
            }

            $fieldValue = $propertyAccessor->getValue($value, $dtoField);

            if (in_array($dtoField, (array) $constraint->nullComparisonForFields) && null === $fieldValue) {
                $qb->andWhere($qb->expr()->isNull("e.$entityField"));
            } elseif (is_string($fieldValue) && $constraint->caseInsensitive) {
                $qb->andWhere($qb->expr()->eq("LOWER(e.$entityField)", "LOWER(:f_$entityField)"));
                $qb->setParameter("f_$entityField", mb_strtolower($fieldValue));
            } else {
                $qb->andWhere($qb->expr()->eq("e.$entityField", ":f_$entityField"));
                $qb->setParameter("f_$entityField", $fieldValue);
            }
        }

        foreach ((array) $constraint->idFields as $dtoField => $entityField) {
            if (is_int($dtoField)) {
                $dtoField = $entityField;
            }

            $fieldValue = $propertyAccessor->getValue($value, $dtoField);

            if ($fieldValue !== null) {
                $qb->andWhere($qb->expr()->neq("e.$entityField", ":i_$entityField"));
                $qb->setParameter("i_$entityField", $fieldValue);
            }
        }

        $count = $qb->getQuery()->getSingleScalarResult();

        if ($count > 0) {
            $constraint->errorPaths = (array) $constraint->errorPaths;

            foreach ($constraint->errorPaths as $errorPath) {
                $this->context->buildViolation($constraint->message)
                    ->setCode(UniqueEntity::NOT_UNIQUE_ERROR)
                    ->atPath($errorPath)
                    ->addViolation();
            }
        }
    }
}
