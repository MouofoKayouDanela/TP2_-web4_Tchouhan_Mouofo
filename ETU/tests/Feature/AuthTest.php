<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Laravel\Sanctum\Sanctum;
use App\Http\Controllers\Controller;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    public function test_register_Avec_Login_Absent_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'password' => 'ValidPass123!',
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['login']);
        $response->assertStatus(INVALID_DATA);
    }


    public function test_register_Avec_Login_Vide_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => '',
            'password' => 'ValidPass123!',
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['login']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_register_Avec_Login_Existant_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $existingUser = User::factory()->create([
            'login' => 'existinguser',
        ]);
    
        $payload = [
            'login' => 'existinguser',
            'password' => 'ValidPass123!',
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['login']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_register_Avec_Login_Non_String_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $existingUser = User::factory()->create([
            'login' => 'existinguser',
        ]);
    
        $payload = [
            'login' => 12345,
            'password' => 'ValidPass123!',
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['login']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_register_Avec_Login_Avec_Plus_50_Caracteres_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $existingUser = User::factory()->create([
            'login' => 'existinguser',
        ]);
    
        $payload = [
            'login' => str_repeat('a', 51),
            'password' => 'ValidPass123!',
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['login']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_register_Avec_password_Absent_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser',           
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_register_Avec_password_Vide_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => '',          
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',

        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

     public function test_register_Avec_password_Sans_Majuscule_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => 'validpass123!',          
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }


     public function test_register_Avec_password_Sans_Minuscule_Retourne_Invalid_data(): void
    {
      
    
        $payload = [
            'login' => 'testuser', 
            'password' => 'VALIDPASS123!',          
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }


     public function test_register_Avec_password_Sans_Symbole_Retourne_Invalid_data(): void
    {
       
    
        $payload = [
            'login' => 'testuser', 
            'password' => 'validpass123',          
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

     public function test_register_Avec_password_Sans_Chiffre_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => 'validpass!!!',          
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

     public function test_register_Avec_password_Avec_Moins_De_huit_Caracteres_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => 'val!55',          
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

      public function test_register_Avec_password_Avec_Plus_De_deux_cent_cinquante_cinq_Caracteres_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => str_repeat('a', 256),         
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

     public function test_register_Avec_Email_Absent_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser',
            'password' => 'ValidPass123!',           
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['email']);
        $response->assertStatus(INVALID_DATA);
    }


    public function test_register_Avec_Email_Vide_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser',
            'password' => 'ValidPass123!',
            'email' => '',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['email']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_register_Avec_Email_Existant_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $existingUser = User::factory()->create([
            'email' => 'test@example.com',
        ]);
    
        $payload = [
            'login' => 'existinguser',
            'password' => 'ValidPass123!',
            'email' => 'test@example.com',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['email']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_register_Avec_Email_Non_String_Retourne_Invalid_data(): void
    {
        $this->seed();   
 
        $payload = [
            'login' => 'testuser',
            'password' => 'ValidPass123!',
            'email' => 12345,
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['email']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_register_Avec_Email_Avec_Plus_50_Caracteres_Retourne_Invalid_data(): void
    {
        $this->seed();   
     
        $payload = [
            'login' => 'testuser',
            'password' => 'ValidPass123!',
            'email' => str_repeat('a', 51),
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['email']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_register_Avec_Email_Qui_N_Est_Pas_Un_Email_Retourne_Invalid_data(): void
    {
        $this->seed();   
      
        $payload = [
            'login' => 'testuser',
            'password' => 'ValidPass123!',
            'email' => 'notanemail',
            'last_name' => 'Doe',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['email']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_register_Avec_last_name_Absent_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser',
            'password' => 'ValidPass123!',           
            'email' => 'test@example.com',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['last_name']);
        $response->assertStatus(INVALID_DATA);
    }


    public function test_register_Avec_Last_Name_Vide_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser',
            'password' => 'ValidPass123!',
            'email' => 'test@example.com',
            'last_name' => '',
            'first_name' => 'John',
        ];      
            
        $response = $this->postJson('/api/register', $payload);

        $response->assertJsonValidationErrors(['last_name']);
        $response->assertStatus(INVALID_DATA);
    }

        public function test_register_Avec_Last_Name_Non_String_Retourne_Invalid_data(): void
        {
            $this->seed();      
          
            $payload = [
                'login' => 'testuser',
                'password' => 'ValidPass123!',
                'email' => 'test@example.com',
                'last_name' => 12345,
                'first_name' => 'John',
            ];      
            
            $response = $this->postJson('/api/register', $payload);

            $response->assertJsonValidationErrors(['last_name']);
            $response->assertStatus(INVALID_DATA);
        }

        public function test_register_Avec_Last_Name_Avec_Plus_50_Caracteres_Retourne_Invalid_data(): void
        {
            $this->seed();        
         
            $payload = [
                'login' => 'testuser',
                'password' => 'ValidPass123!',
                'email' => 'test@example.com',
                'last_name' => str_repeat('a', 51), 
                'first_name' => 'John',
            ];      
                
            $response = $this->postJson('/api/register', $payload);

            $response->assertJsonValidationErrors(['last_name']);
            $response->assertStatus(INVALID_DATA);
        }

        public function test_register_Avec_first_name_Absent_Retourne_Invalid_data(): void
        {
            $this->seed();
        
            $payload = [
                'login' => 'testuser',
                'password' => 'ValidPass123!',           
                'email' => 'test@example.com',
                'last_name' => 'John',
            ];      
                
            $response = $this->postJson('/api/register', $payload);

            $response->assertJsonValidationErrors(['first_name']);
            $response->assertStatus(INVALID_DATA);
        }


        public function test_register_Avec_First_Name_Vide_Retourne_Invalid_data(): void
        {
            $this->seed();
        
            $payload = [
                'login' => 'testuser',
                'password' => 'ValidPass123!',
                'email' => 'test@example.com',
                'first_name' => '',
                'last_name' => 'Doe',
            ];      
                
            $response = $this->postJson('/api/register', $payload);

            $response->assertJsonValidationErrors(['first_name']);
            $response->assertStatus(INVALID_DATA);
        }

        public function test_register_Avec_First_Name_Non_String_Retourne_Invalid_data(): void
        {
            $this->seed();        
        
            $payload = [
                'login' => 'testuser',
                'password' => 'ValidPass123!',
                'email' => 'test@example.com',
                'last_name' => 'Doe',
                'first_name' => 12345,
            ];      
            
            $response = $this->postJson('/api/register', $payload);

            $response->assertJsonValidationErrors(['first_name']);
            $response->assertStatus(INVALID_DATA);
        }

        public function test_register_Avec_First_Name_Avec_Plus_50_Caracteres_Retourne_Invalid_data(): void
        {
            $this->seed();        
        
            $payload = [
                'login' => 'testuser',
                'password' => 'ValidPass123!',
                'email' => 'test@example.com',
                'last_name' => 'Doe',
                'first_name' => str_repeat('a', 51),
            ];      
                
            $response = $this->postJson('/api/register', $payload);

            $response->assertJsonValidationErrors(['first_name']);
            $response->assertStatus(INVALID_DATA);
        }


        public function test_register_Avec_Payload_Valide_Retourne_Success(): void
        {
            $this->seed();       
           
        
            $payload = [
                'login' => 'testuser',
                'password' => 'ValidPass123!',
                'email' => 'test@example.com',
                'last_name' => 'Doe',
                'first_name' => 'John',
                'role_id'    => 1
            ];      
                
            $response = $this->postJson('/api/register', $payload);
           
            $response->assertStatus(CREATED);
            
            $response->assertJsonMissingPath('errors');
               
            $response->assertJsonStructure([
                'access_token'
            ]);

            $this->assertNotEmpty($response->json('access_token'));

            $this->assertDatabaseHas('users', [
                'login' => 'testuser',
                'email' => 'test@example.com',
                'last_name' => 'Doe',
                'first_name' => 'John',
            ]);
        }

        public function test_register_Avec_Plus_De_Cinq_Requetes_En_Une_Minute_Retourne_ToManyRequest(): void
        {
            $this->seed();       


            for ($i = 0; $i < 6; $i++) {
                $payload = [
                    'login' => 'testuser' . $i,
                    'password' => 'ValidPass123!',
                    'email' => 'test' . $i . '@example.com',
                    'last_name' => 'Doe',
                    'first_name' => 'John',
                ];                     
            $response = $this->postJson('/api/register', $payload);
            }

            $response->assertStatus(TOO_MANY_REQUESTS); 
            
        }

        

public function test_login_Avec_Login_Absent_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'password' => 'ValidPass123!',           
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['login']);
        $response->assertStatus(INVALID_DATA);
    }


    public function test_login_Avec_Login_Vide_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => '',
            'password' => 'ValidPass123!',           
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['login']);
        $response->assertStatus(INVALID_DATA);
    }

    

    public function test_login_Avec_Login_Non_String_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $existingUser = User::factory()->create([
            'login' => 'existinguser',
        ]);
    
        $payload = [
            'login' => 12345,
            'password' => 'ValidPass123!',
            
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['login']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_login_Avec_Login_Avec_Plus_50_Caracteres_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $existingUser = User::factory()->create([
            'login' => 'existinguser',
        ]);
    
        $payload = [
            'login' => str_repeat('a', 51),
            'password' => 'ValidPass123!',
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['login']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_login_Avec_password_Absent_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser',           
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_login_Avec_password_Vide_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => '',          
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

     public function test_login_Avec_password_Sans_Majuscule_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => 'validpass123!',          
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }


     public function test_login_Avec_password_Sans_Minuscule_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => 'VALIDPASS123!',          
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }


     public function test_login_Avec_password_Sans_Symbole_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => 'validpass123',          
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

     public function test_login_Avec_password_Sans_Chiffre_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => 'validpass!!!',          
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

     public function test_login_Avec_password_Avec_Moins_De_huit_Caracteres_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => 'val!55',          
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

    public function test_login_Avec_password_Avec_Plus_De_deux_cent_cinquante_cinq_Caracteres_Retourne_Invalid_data(): void
    {
        $this->seed();
    
        $payload = [
            'login' => 'testuser', 
            'password' => str_repeat('a', 256),         
        ];      
            
        $response = $this->postJson('/api/login', $payload);

        $response->assertJsonValidationErrors(['password']);
        $response->assertStatus(INVALID_DATA);
    }

     public function test_login_Avec_Payload_Valide_Retourne_Success(): void
    {
        $this->seed();       
        
        $user = User::factory()->create([
            'login' => 'testuser',
            'password' => bcrypt('ValidPass123!'),
        ]);
    
        $payload = [
            'login' => 'testuser',
            'password' => 'ValidPass123!',          
        ];      
            
        $response = $this->postJson('/api/login', $payload);
       
        $response->assertStatus(OK);
        
        $response->assertJsonMissingPath('errors');
           
        $response->assertJsonStructure([
            'auth_token'
        ]);

        $this->assertNotEmpty($response->json('auth_token'));
    }

    public function test_login_Avec_Plus_De_Cinq_Requetes_En_Une_Minute_Retourne_ToManyRequest(): void
    {
           Role::factory()->create(['id' => 1, 'name' => 'USER']);
         $user = User::factory()->create([
            'login' => 'testuser',
            'password' => bcrypt('ValidPass123!'),
        ]);

        $payload = [
            'login' => 'testuser', 
            'password' => 'ValidPass123!',
        ];
        
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/login', $payload);
            $response->assertStatus(OK); 
        }
        
        $response = $this->postJson('/api/login', $payload);
        $response->assertStatus(TOO_MANY_REQUESTS); 
    }

    
    public function test_logout_Retourne_OK(): void
    {   
        Role::factory()->create(['id' => 1, 'name' => 'USER']);        
        
        $user = User::factory()->create();

         Sanctum::actingAs($user);
        
        $response = $this->postJson('/api/logout');
       
        $response->assertStatus(OK);      
        $response->assertJson([
            'message' => 'Successfully logged out'
        ]);

        $this->assertCount(0, $user->tokens);
    }

    public function test_logout_Avec_Token_Invalide_Retourne_UNAUTHORIZED(): void 
    {
        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer token_invalide_123'
        ]);
        
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }


    public function test_route_logout_Est_Protegee_Par_Authentification(): void 
    {
        $response = $this->postJson('/api/logout');
        
        $response->assertStatus(UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }
}