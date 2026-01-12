<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Film;
use App\Models\Critic;
use App\Models\Language;
use App\Models\Role;
use Laravel\Sanctum\Sanctum;

class CriticTest extends TestCase
{
    use RefreshDatabase;

     public function test_store_sans_authentification_retourne_401(): void
    {
        $this->postJson('/api/critic', [])->assertStatus(UNAUTHORIZED);
    }

    public function test_store_champs_invalides_retourne_422(): void
    {
            $role = Role::factory()->user()->create();
            $user = User::factory()->create(['role_id' => $role->id]);
            Sanctum::actingAs($user);

            $language = Language::factory()->create();
            $film = Film::factory()->create(['language_id' => $language ->id]);

        $this->postJson('/api/critic', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['user_id', 'film_id', 'score', 'comment']);
    }

    public function test_store_critique_existante_retourne_409(): void
    {
        
            $role = Role::factory()->user()->create();
            $user = User::factory()->create(['role_id' => $role->id]);
            Sanctum::actingAs($user);

            $language = Language::factory()->create();
            $film = Film::factory()->create(['language_id' => $language ->id]);

        Critic::factory()->create([
            'user_id' => $user->id,
            'film_id' => $film->id,
        ]);

        $payload = [
            'user_id' => $user->id,
            'film_id' => $film->id,
            'score' => 4,
            'comment' => 'Très bon film',
        ];

        $this->postJson('/api/critic', $payload)
            ->assertStatus(CONFLICT)
            ->assertJsonFragment([
                'message' => 'Vous avez déjà critiqué ce film.',
            ]);
    }

    public function test_store_valide_retourne_201(): void
    {
        
            $role = Role::factory()->user()->create();
            $user = User::factory()->create(['role_id' => $role->id]);
            Sanctum::actingAs($user);

            $language = Language::factory()->create();
            $film = Film::factory()->create(['language_id' => $language ->id]);

        $payload = [
            'user_id' => $user->id,
            'film_id' => $film->id,
            'score' => 5,
            'comment' => 'Chef d’œuvre',
        ];

        $this->postJson('/api/critic', $payload)
            ->assertStatus(CREATED)
            ->assertJsonMissingPath('errors');

        $this->assertDatabaseHas('critics', [
            'user_id' => $user->id,
            'film_id' => $film->id,
        ]);
    }
   
}
