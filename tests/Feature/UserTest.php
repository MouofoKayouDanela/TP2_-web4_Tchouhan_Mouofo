<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;



class UserTest extends TestCase
{

  use RefreshDatabase;

    public function test_UtilisateurAuthentifie_PeutVoirSonProfil()
    {
        Role::factory()->create(['id' => 1, 'name' => 'USER']);
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson("/api/user/{$user->id}")
            ->assertStatus(200);
    }

    public function test_UtilisateurNonAuthentifie_NePeutPasVoirProfil()
    {
        Role::factory()->create(['id' => 1, 'name' => 'USER']);
        $user = User::factory()->create();

        $this->getJson("/api/user/{$user->id}")
            ->assertStatus(401);
    }

    public function test_UtilisateurNePeutPasVoirProfil_DunAutreUtilisateur()
    {
        Role::factory()->create(['id' => 1, 'name' => 'USER']);
        $user = User::factory()->create();
        $autre = User::factory()->create();

        Sanctum::actingAs($user);

        $this->getJson("/api/user/{$autre->id}")
            ->assertStatus(403);
    }

    public function test_Retourne404_SiUtilisateurInexistant()
    {
        Role::factory()->create(['id' => 1, 'name' => 'USER']);
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson("/api/user/999")
            ->assertStatus(404);
    }

    public function test_UtilisateurAuthentifie_PeutModifierSonMotDePasse()
    {
        Role::factory()->create(['id' => 1, 'name' => 'USER']);
        $user = User::factory()->create([
            'password' => bcrypt('Ancienmdp1!'),
        ]);

        Sanctum::actingAs($user);

        $this->putJson("/api/user/{$user->id}", [
            'oldpassword' => 'Ancienmdp1!',
            'newpassword' => 'Nouveaumdp123!',
            'newpassword_confirmation' => 'Nouveaumdp123!',
        ])->assertStatus(200);
    }

    public function test_Utilisateur_NePeutPasModifierMotDePasseSiAncienMotDePasseIncorrect()
    {
        Role::factory()->create(['id' => 1, 'name' => 'USER']);
        $user = User::factory()->create(['password' => bcrypt('Ancienmdp1!')]);
        Sanctum::actingAs($user);

        $this->putJson("/api/user/{$user->id}", [
            'oldpassword' => 'fauxmdp',
            'newpassword' => 'nouveaumdp123',
            'newpassword_confirmation' => 'nouveaumdp123',
        ])->assertStatus(422);
    }

    public function testUtilisateur_NePeutPasModifierMotDePasse_AvecDonneesInvalides()
    {
        Role::factory()->create(['id' => 1, 'name' => 'USER']);
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->putJson("/api/user/{$user->id}", [
            'oldpassword' => '',
            'newpassword' => '123',
            'newpassword_confirmation' => '456',
        ])->assertStatus(422);
    }

    public function testUtilisateurNePeutPasModifierMotDePasseDunAutreUtilisateur()
    {
        Role::factory()->create(['id' => 1, 'name' => 'USER']);
        $user = User::factory()->create(['password' => bcrypt('Ancienmdp1!')]);
        $autre = User::factory()->create();

        Sanctum::actingAs($user);

        $this->putJson("/api/user/{$autre->id}", [
            'oldpassword' => 'Ancienmdp1!',
            'newpassword' => 'nNouveaumdp123!',
            'newpassword_confirmation' => 'Nouveaumdp123!',
        ])->assertStatus(403);
    }

    public function test_UtilisateurNonAuthentifie_NePeutPasModifierMotDePasse()
    {
        Role::factory()->create(['id' => 1, 'name' => 'USER']);
        $user = User::factory()->create();

        $this->putJson("/api/user/{$user->id}", [
            'oldpassword' => 'Ancienmdp1!',
            'newpassword' => 'Nouveaumdp123!',
            'newpassword_confirmation' => 'Nouveaumdp123!',
        ])->assertStatus(401);
    }
}
