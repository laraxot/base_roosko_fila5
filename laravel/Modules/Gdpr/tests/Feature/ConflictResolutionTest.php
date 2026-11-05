<?php

declare(strict_types=1);

use Modules\Gdpr\Models\Profile;
use Modules\Gdpr\Models\Treatment;
use PHPUnit\Framework\Assert;

it('verifica che le classi corrette siano istanziabili', function (): void {
<<<<<<< HEAD
    Assert::assertInstanceOf(Treatment::class, new Treatment());
    Assert::assertInstanceOf(Profile::class, new Profile());
=======
    Assert::assertSame('treatments', (new Treatment())->getTable());
    Assert::assertSame('profiles', (new Profile())->getTable());
>>>>>>> laraxot/dev
});

it('verifica che le proprietà delle classi siano accessibili', function (): void {
    $treatment = new Treatment();
    $profile = new Profile();

    Assert::assertIsArray($treatment->getFillable());
    Assert::assertIsArray($profile->getFillable());
    Assert::assertSame('gdpr', $profile->getConnectionName());
});
