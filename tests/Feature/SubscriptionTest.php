<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    protected $user;
    protected $plan;
    protected $newPlan;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->plan = Plan::factory()->create(['name' => 'standard']);
        $this->newPlan = Plan::factory()->create();
    }

    public function testNoSubscriptions()
    {
        $this->assertNull($this->user->subscriptions()->first());
        $this->assertNull($this->user->activeSubscription());
        // $this->assertNull($this->user->lastActiveSubscription());
        $this->assertFalse($this->user->hasActiveSubscription());
    }

    public function testSubscribeToWithInvalidDuration()
    {
        $this->assertFalse($this->user->subscribeTo($this->plan, 0));
        $this->assertFalse($this->user->subscribeTo($this->plan, -1));
    }

    /** @test */
    public function a_user_can_subscribe_to_a_plan()
    {
        $this->user->subscribeTo($this->plan);
        $this->assertEquals('standard', $this->user->activeSubscription()->plan->name);
    }
    
    /** @test */
    public function a_user_can_only_have_one_active_plan()
    {
        $plan2 = Plan::factory()->create(['name' => 'premium']);
        $this->user->subscribeTo($this->plan);
        $this->assertFalse($this->user->subscribeTo($plan2));
    }

    /** @test */
    public function a_user_can_cancel_their_plan(){
      $this->user->subscribeTo($this->plan);

      $this->user->cancelCurrentSubscription();

      $this->assertTrue($this->user->activeSubscription()->isCancelled());
    }

    public function testSubscribeTo()
    {
        $subscription = $this->user->subscribeTo($this->plan, 15);
        sleep(1);

        $this->assertEquals($subscription->plan_id, $this->plan->id);
        $this->assertNotNull($this->user->subscriptions()->first());
        $this->assertNotNull($this->user->activeSubscription());
        // $this->assertNotNull($this->user->lastActiveSubscription());
        $this->assertTrue($this->user->hasActiveSubscription());
        $this->assertEquals($subscription->remainingDays(), 14);
    }
}
