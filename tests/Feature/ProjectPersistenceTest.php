<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_project_is_persisted_with_its_owner(): void
    {
        $owner = User::factory()->create();

        $project = Project::factory()->for($owner, 'owner')->create([
            'name' => 'AI Project Assistant',
            'description' => 'A project and task management API.',
        ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $owner->id,
            'name' => 'AI Project Assistant',
        ]);
        $this->assertTrue($project->owner->is($owner));
        $this->assertTrue($owner->projects()->whereKey($project)->exists());
    }
}
