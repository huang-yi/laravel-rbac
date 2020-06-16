<?php

namespace HuangYi\Rbac\Tests\Directives;

use HuangYi\Rbac\Permission;
use HuangYi\Rbac\Tests\TestCase;
use HuangYi\Rbac\Tests\User;

class PermissionDirectivesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['router']->get('view/permission', function () {
            return view('rbac::permission');
        });
    }

    /** @test */
    public function it_should_see_correct_permissions()
    {
        $user = factory(User::class)->create();

        foreach (['1', '3', '5', '6', 'c'] as $num) {
            $user->permissions()->attach(
                factory(Permission::class)->create(['name' => 'p'.$num])
            );
        }

        $response = $this->actingAs($user)->get('view/permission');

        $response->assertSeeText('p1')
            ->assertDontSeeText('p2')
            ->assertDontSeeText('p3')
            ->assertSeeText('p4')
            ->assertSeeText('p5&p6', false)
            ->assertDontSeeText('p7&p8', false)
            ->assertDontSeeText('p9|pa', false)
            ->assertSeeText('pb|pc', false);
    }
}
