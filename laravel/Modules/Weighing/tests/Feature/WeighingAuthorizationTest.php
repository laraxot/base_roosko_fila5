<?php

declare(strict_types=1);

use Filament\Actions\Action;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Weighing\Database\Seeders\WeighingPermissionSeeder;
use Modules\Weighing\Filament\Actions\ReadScaleFieldAction;
use Modules\Weighing\Filament\Actions\ReadScaleHeaderAction;
use Modules\Weighing\Filament\Resources\WeighingResource\Pages\ListWeighings;
use Modules\Weighing\Filament\Resources\WeighingResource\Pages\ViewWeighing;
use Modules\Weighing\Models\Policies\ScalePolicy;
use Modules\Weighing\Models\Policies\WeighingPolicy;
use Modules\Weighing\Models\Scale;
use Modules\Weighing\Models\Weighing;
use Modules\Weighing\Tests\TestCase;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Models\Policies\Concerns\DeniesUndeclaredFilamentAbilities;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/** GenericUser che implementa Authorizable, come il Gate::before di spatie/permission richiede. */
class WeighingAuthorizableUser extends GenericUser implements AuthorizableContract
{
    use Authorizable;
}

/**
 * Utente autenticato fittizio: le abilita' consentite le decide un Gate::before del test,
 * cosi' il test non dipende da tabelle e permessi del modulo User.
 *
 * @param  list<string>  $abilities
 */
function weighingActingWith(array $abilities): void
{
    Auth::setUser(new WeighingAuthorizableUser(['id' => 1]));
    Gate::before(static fn (mixed $_user, string $ability): bool => in_array($ability, $abilities, true));
}

/**
 * Header action di una pagina della resource, costruita senza montare il componente Livewire.
 */
function weighingPageAction(string $pageClass, string $name, ?Weighing $record = null): Action
{
    $page = new $pageClass;
    $method = new ReflectionMethod($page, 'getHeaderActions');
    $actions = $method->invoke($page);
    Assert::assertIsArray($actions);
    $action = $actions[$name] ?? null;
    Assert::assertInstanceOf(Action::class, $action);

    return $record === null ? $action : $action->record($record);
}

/** @return list<class-string<ScalePolicy|WeighingPolicy>> */
function weighingPolicies(): array
{
    return [ScalePolicy::class, WeighingPolicy::class];
}

describe('Weighing policies', function (): void {
    test('every policy denies the Filament abilities it does not declare', function (): void {
        $user = Mockery::mock(UserContract::class);
        Assert::assertInstanceOf(UserContract::class, $user);

        foreach (weighingPolicies() as $class) {
            Assert::assertContains(DeniesUndeclaredFilamentAbilities::class, class_uses($class), $class);

            $policy = new $class;
            Assert::assertFalse($policy->deleteAny($user), $class);
            Assert::assertFalse($policy->forceDeleteAny($user), $class);
            Assert::assertFalse($policy->reorder($user), $class);
        }
    });

    test('reading a scale and voiding a ticket need their own permissions', function (): void {
        $user = Mockery::mock(UserContract::class);
        $user->shouldReceive('hasPermissionTo')->with('scale.readScale')->once()->andReturn(false);
        $user->shouldReceive('hasPermissionTo')->with('weighing.void')->once()->andReturn(true);
        Assert::assertInstanceOf(UserContract::class, $user);

        Assert::assertFalse((new ScalePolicy)->readScale($user));
        Assert::assertTrue((new WeighingPolicy)->void($user));
    });

    test('the permission seeder creates every permission checked by the policies', function (): void {
        $used = [];
        foreach (weighingPolicies() as $class) {
            $file = (new ReflectionClass($class))->getFileName();
            Assert::assertIsString($file);
            preg_match_all("/hasPermissionTo\\('([^']+)'\\)/", (string) file_get_contents($file), $matches);
            $used = [...$used, ...$matches[1]];
        }

        $used = array_values(array_unique($used));
        sort($used);
        $seeded = WeighingPermissionSeeder::PERMISSIONS;
        sort($seeded);

        Assert::assertSame($used, $seeded);
    });
});

describe('Weighing actions require an ability', function (): void {
    test('without abilities read scale, open, single, close and void are unauthorized', function (): void {
        weighingActingWith([]);
        $weighing = new Weighing;

        Assert::assertFalse(ReadScaleHeaderAction::make()->record(new Scale)->isAuthorized());
        Assert::assertFalse(ReadScaleFieldAction::make()->isAuthorized());
        Assert::assertFalse(weighingPageAction(ListWeighings::class, 'open_weighing')->isAuthorized());
        Assert::assertFalse(weighingPageAction(ListWeighings::class, 'record_single_weighing')->isAuthorized());
        Assert::assertFalse(weighingPageAction(ViewWeighing::class, 'close_weighing', $weighing)->isAuthorized());
        Assert::assertFalse(weighingPageAction(ViewWeighing::class, 'void_weighing', $weighing)->isAuthorized());
    });

    test('each action is authorized by its own ability', function (): void {
        weighingActingWith(['readScale', 'create', 'update', 'void']);
        $weighing = new Weighing;

        Assert::assertTrue(ReadScaleHeaderAction::make()->record(new Scale)->isAuthorized());
        Assert::assertTrue(ReadScaleFieldAction::make()->isAuthorized());
        Assert::assertTrue(weighingPageAction(ListWeighings::class, 'open_weighing')->isAuthorized());
        Assert::assertTrue(weighingPageAction(ListWeighings::class, 'record_single_weighing')->isAuthorized());
        Assert::assertTrue(weighingPageAction(ViewWeighing::class, 'close_weighing', $weighing)->isAuthorized());
        Assert::assertTrue(weighingPageAction(ViewWeighing::class, 'void_weighing', $weighing)->isAuthorized());
    });

    test('closing does not grant voiding', function (): void {
        weighingActingWith(['update']);
        $weighing = new Weighing;

        Assert::assertTrue(weighingPageAction(ViewWeighing::class, 'close_weighing', $weighing)->isAuthorized());
        Assert::assertFalse(weighingPageAction(ViewWeighing::class, 'void_weighing', $weighing)->isAuthorized());
    });

    test('scale reads are rate limited', function (): void {
        config(['weighing.read_rate_limit' => 5]);

        Assert::assertSame(5, ReadScaleHeaderAction::make()->getRateLimit());
        Assert::assertSame(5, ReadScaleFieldAction::make()->getRateLimit());
    });
});
