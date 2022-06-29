<?php

namespace Tests\Unit;

use App\Http\Middleware\CheckUser;

use Faker\Factory;

use PHPUnit\Framework\TestCase;

use Illuminate\Http\Request;
use App\User;
class PermissionsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function admins_are_allowed_check_admin_middleware()
    {
        $user = User::factory()->make();

        $request = Request::create('/report/create', 'GET');

        $middleware = new CheckUser;

        $response = $middleware->handle($request, function () {});

        $this->assertEquals($response->getStatusCode(), 403);
    }
  
}
