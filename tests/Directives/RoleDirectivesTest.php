<?php

namespace HuangYi\Rbac\Tests\Directives;

use HuangYi\Rbac\Permission;
use HuangYi\Rbac\Role;
use HuangYi\Rbac\Tests\TestCase;
use HuangYi\Rbac\Tests\User;

class RoleDirectivesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['router']->get('view/role', function () {
            return view('rbac::role');
        });
    }

    /** @test */
    public function it_should_see_correct_permissions()
    {
        $user = factory(User::class)->create();

        foreach (['1', '3', '5', '6', 'c'] as $num) {
            $user->roles()->attach(
                factory(Role::class)->create(['name' => 'r'.$num])
            );
        }

        $response = $this->actingAs($user)->get('view/role');

        $response->assertSeeText('r1')
            ->assertDontSeeText('r2')
            ->assertDontSeeText('r3')
            ->assertSeeText('r4')
            ->assertSeeText('r5&r6', false)
            ->assertDontSeeText('r7&r8', false)
            ->assertDontSeeText('r9|ra', false)
            ->assertSeeText('rb|rc', false);
    }
}
