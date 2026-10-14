<?php

namespace Base\Scholar\Tests\Controller;

use Base\Admin\Test\CrudFieldsTestCase;
use Base\Scholar\Controller\Admin\Crud\Scholar\PublicationCrudController;

/**
 * The fields marked required on this bundle's back office screens, against
 * what its records need (see CrudFieldsTestCase): only those the record
 * cannot do without, all of them, an optional one able to take an empty
 * value, one field per property, a slug following its title.
 */
final class CrudRequiredFieldsTest extends CrudFieldsTestCase
{
    protected static function sourceDirectory(): string
    {
        return __DIR__.'/../../src';
    }

    protected static function skipped(): array
    {
        // Required by the bundle, missing from omnibase's harness: read where it is installed.
        return class_exists('Omnischolar\Model\WorkType') ? [] : [PublicationCrudController::class => "a publication's kinds are glitchr/omnischolar's, absent from this installation"];
    }
}
