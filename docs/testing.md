---
title: "Testing"
order: 60
---

# Testing

## Running the suite

From the package's checkout, with its dev dependencies:

```bash
vendor/bin/phpunit
```

Inside a host application:

```bash
php vendor/bin/phpunit -c vendor/omnibase/scholar/phpunit.xml.dist
```

The suite needs omnisense/omnischolar. omnisense/omnibase-harness does not
have it, and its `test scholar` stops when the kernel boots
(`Class "Omnischolar\Export" not found`); with omnisense/omnischolar's
packages added to the harness's autoloader (its checkouts mounted), the
suite on this checkout gives:

```text
OK (40 tests, 257 assertions)
```

## What the tests check

| Test | Checks |
|---|---|
| `Controller\Admin\OpenToAdminsTest` | the administrator writes in the nine screens; anyone else is refused |
| `Controller\CrudRequiredFieldsTest` | omnibase/admin's `CrudFieldsTestCase` on the nine screens |
| `Entity\EnumFieldsTest` | a publication holds its status, a theme its kind, a supervision its level; the columns are strings of the enum's length; each case names itself in the `scholar` domain |
| `Service\CitationsTest` | the reference line; the exports carry the site's overrides; the JSON-LD |
| `Service\SynchronizerTest` | a new work waits to be validated; the site's overrides survive the next run; a work is found again by any identifier ever seen; a rejected work stays rejected and a manual one stays the site's; an auto-approving scholar's works go online at once |
| `Service\WorkDataTest` | a work comes back whole; a book's editions come back and name it; the authors' line |
