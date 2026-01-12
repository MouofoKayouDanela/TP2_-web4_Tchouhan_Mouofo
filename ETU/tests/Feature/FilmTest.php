<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Language;
use App\Models\Film;
use Tests\TestCase;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Laravel\Sanctum\Sanctum;


class FilmTest extends TestCase
{
         use RefreshDatabase;

        
        public function test_store_sans_authentification_retourne_401(): void
        {
            $language = Language::factory()->create();

            $payload = [
                'title' => 'Test Film',
                'release_year' => 2024,
                'length' => 120,
                'description' => 'Test description',
                'rating' => 'PG-13',
                'special_features' => 'Trailers',
                'image' => 'test.jpg',
                'language_id' => $language->id,
            ];

            $response = $this->postJson('/api/film', $payload);

            $response->assertStatus(UNAUTHORIZED);

            $response->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ]);
        }


        public function test_store_Avec_Request_champs_vide_Retourne_Invalid_data(): void
        {
            $role = Role::factory()->admin()->create();

            $adminUser = User::factory()->create([
                'role_id' => $role->id,
            ]);

            $language=Language::factory()->create();

            Sanctum::actingAs($adminUser);

            $payload = [
                'title' => '',
                'release_year' => '',
                'length' => '',
                'description' => '',
                'rating' => '',
                'special_features' => '',
                'image' => '',
                'language_id' => '',
            ];

            $response = $this->postJson('/api/film', $payload);

            $response->assertUnprocessable();

            $response->assertJsonValidationErrors([
                'release_year',
                'length',
                'description',
                'rating',
                'special_features',
                'image',
                'language_id',
            ]);
        }



        public function test_store_Avec_Request_Valide_User_Admin_Retourne_Success(): void
        {
            $role = Role::factory()->admin()->create();

            $adminUser = User::factory()->create([
                'role_id' => $role->id,
            ]);

            $language=Language::factory()->create();

            Sanctum::actingAs($adminUser);

            $payload = [
                'title' => 'Inception',
                'release_year' => 2024,
                'length' => 120,
                'description' => 'Test description',
                'rating' => 'PG-13',
                'special_features' => 'Trailers',
                'image' => 'test.jpg',
                'language_id' => $language->id,
            ];

            $response = $this->postJson('/api/film', $payload);

            $response->assertStatus(CREATED);
            
            $response->assertJsonMissingPath('errors');
           
        }

        public function test_store_Avec_Request_Valide_user_Pas_admin_Retourne_403(): void
        {
            $role = Role::factory()->user()->create();

            $adminUser = User::factory()->create([
                'role_id' => $role->id,
            ]);

            $language=Language::factory()->create();

            Sanctum::actingAs($adminUser);

            $payload = [
                'title' => 'Inception',
                'release_year' => 2024,
                'length' => 120,
                'description' => 'Test description',
                'rating' => 'PG-13',
                'special_features' => 'Trailers',
                'image' => 'test.jpg',
                'language_id' => $language->id,
            ];

            $response = $this->postJson('/api/film', $payload);

           $response->assertStatus(FORBIDDEN);
           
        }

        public function test_store_Avec_Plus_De_Soixante_Requetes_En_Une_Minute_Retourne_TooManyRequests(): void
        {
           
           $role = Role::factory()->admin()->create();

            $adminUser = User::factory()->create([
                'role_id' => $role->id,
            ]);

            $language=Language::factory()->create();

            Sanctum::actingAs($adminUser);        
           
            $payload = [
                'title' => 'Test Film',
                'release_year' => 2024,
                'length' => 120,
                'description' => 'Test description',
                'rating' => 'PG-13',
                'special_features' => 'Trailers',
                'image' => 'test.jpg',
                'language_id' => $language->id,
            ];  

            
            for ($i = 0; $i <= 61; $i++) {
                $response = $this->postJson('/api/film', $payload);
            }
            $response->assertStatus(TOO_MANY_REQUESTS);
        }

        public function test_update_sans_authentification_retourne_401(): void
        {
            
            $language = Language::factory()->create();
            $film = Film::factory()->create(['language_id' => $language ->id]);

            $payload = [
                'title' => 'Nouveau titre',
                'release_year' => 2025,
                'length' => 130,
                'description' => 'Nouvelle description',
                'rating' => 'PG-13',
                'special_features' => 'Trailers',
                'image' => 'new.jpg',
                'language_id' => $language->id,
            ];

            $response = $this->putJson("/api/film/{$film->id}", $payload);

            $response->assertStatus(UNAUTHORIZED);
        }


        public function test_update_avec_donnees_invalides_retourne_422(): void
        {
            $role = Role::factory()->admin()->create();
            $admin = User::factory()->create(['role_id' => $role->id]);
            Sanctum::actingAs($admin);

              
            $language = Language::factory()->create();
            $film = Film::factory()->create(['language_id' => $language ->id]);

            $payload = [
                'title' => '',
                'release_year' => '',
                'length' => '',
                'description' => '',
                'rating' => '',
                'special_features' => '',
                'image' => '',
                'language_id' => '',
            ];

            $response = $this->putJson("/api/film/{$film->id}", $payload);

            $response->assertUnprocessable();
        }

        public function test_update_valide_admin_retourne_200(): void
        {
            $role = Role::factory()->admin()->create();
            $admin = User::factory()->create(['role_id' => $role->id]);
            Sanctum::actingAs($admin);

            
            $language = Language::factory()->create();
            $film = Film::factory()->create(['language_id' => $language ->id]);

            $payload = [
                'title' => 'Titre modifié',
                'release_year' => 2025,
                'length' => 130,
                'description' => 'Description modifiée',
                'rating' => 'PG-13',
                'special_features' => 'Trailers',
                'image' => 'new.jpg',
                'language_id' => $language->id,
            ];

            $response = $this->putJson("/api/film/{$film->id}", $payload);

            $response->assertStatus(OK)
                    ->assertJsonMissingPath('errors');
        }

        public function test_update_user_non_admin_retourne_403(): void
        {
            $role = Role::factory()->user()->create();
            $user = User::factory()->create(['role_id' => $role->id]);
            Sanctum::actingAs($user);

             
            $language = Language::factory()->create();
            $film = Film::factory()->create(['language_id' => $language ->id]);

            $payload = [
                'title' => 'Titre modifié',
                'release_year' => 2025,
                'length' => 130,
                'description' => 'Description modifiée',
                'rating' => 'PG-13',
                'special_features' => 'Trailers',
                'image' => 'new.jpg',
                'language_id' => $language->id,
            ];

            $response = $this->putJson("/api/film/{$film->id}", $payload);

            $response->assertStatus(FORBIDDEN);
        }


        public function test_update_film_inexistant_retourne_404(): void
        {
            $role = Role::factory()->admin()->create();
            $admin = User::factory()->create(['role_id' => $role->id]);
            Sanctum::actingAs($admin);

            $language = Language::factory()->create();

            $payload = [
                'title' => 'Titre modifié',
                'release_year' => 2025,
                'length' => 130,
                'description' => 'Description modifiée',
                'rating' => 'PG-13',
                'special_features' => 'Trailers',
                'image' => 'new.jpg',
                'language_id' => $language->id,
            ];

            $response = $this->putJson("/api/film/999999", $payload);

            $response->assertStatus(NOT_FOUND);
        }

        public function test_destroy_user_non_admin_retourne_403(): void
        {
            $role = Role::factory()->user()->create();
            $user = User::factory()->create(['role_id' => $role->id]);
            Sanctum::actingAs($user);

            $language = Language::factory()->create();
            $film = Film::factory()->create(['language_id' => $language ->id]);


            $this->deleteJson("/api/film/{$film->id}")
                ->assertStatus(FORBIDDEN);
        }

        

        public function test_destroy_admin_peut_supprimer_film(): void
        {
            $role = Role::factory()->admin()->create();
            $admin = User::factory()->create(['role_id' => $role->id]);
            Sanctum::actingAs($admin);

            $language = Language::factory()->create();
            $film = Film::factory()->create(['language_id' => $language ->id]);


            $this->deleteJson("/api/film/{$film->id}")
                ->assertStatus(NO_CONTENT);

            $this->assertDatabaseMissing('films', [
                'id' => $film->id
            ]);
        }
        
        public function test_destroy_admin_film_inexistant_retourne_404(): void
        {
            $role = Role::factory()->admin()->create();
            $admin = User::factory()->create(['role_id' => $role->id]);
            Sanctum::actingAs($admin);

            $this->deleteJson("/api/film/999999")
                ->assertStatus(NOT_FOUND);
        }





}
