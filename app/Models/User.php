<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Casts\Lowercase;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\QueuedResetPassword as QueuedResetPasswordNotification;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token', 'is_admin'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'        =>  'datetime',
            'password'                 =>  'hashed',
            'is_admin'                 =>  'boolean',
            'is_must_change_password'  =>  'boolean',
            'email'                    =>   Lowercase::class,
        ];

    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $this->notify(new QueuedResetPasswordNotification($token));
    }

    public static function whereInLike(string $column, array $values)
    {
        $class = static::class;

        return $class::where(function($query) use ( $column, $values) {

            foreach ($values as $value)
            {
                $query->orWhereLike($column, $value, caseSensitive: false);
            }

        });

    }


    public static function whereLikeCi(string $column, mixed $value)
    {
        return static::whereLike($column, $value, caseSensitive: false);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




    public static function add(string $name, string  $email, string $password)
    {
        try {

            return static::create([

                'name'        =>    $name,
                'email'       =>    $email,
                'password'    =>    $password,

            ]);

        } catch (Exception $e) {

            return null;

        }
    }

    public static function del(int | string | null $id_or_name)
    {
        try {

            if ($id_or_name === null) return false;

            $user = is_string($id_or_name)
                ? static::whereLikeCi('name', $id_or_name)->first()
                : static::find($id_or_name);



            if ($user) return $user->delete();

            return false;

        } catch (Exception $e) {

            return false;

        }
    }

    public static function modify(int $id, ?string $name = null, ?string  $email = null, ?string $password = null)
    {
        try {

            $user = static::find($id);

            if (! $user) return null;

            ! $name                ?:             (     $user->name        =    $name             );
            ! $email               ?:             (     $user->email       =    $email            );
            ! $password            ?:             (     $user->password    =    $password         );

            $user->save();

            return $user;

        } catch (Exception $e) {

            return null;

        }
    }

    /**
     * @return object{id: int, name: string, email: string, password: string}|null
     */
    public static function get(int | string | null $id_or_name): ?object
    {

        if ($id_or_name === null) return null;

        $user = is_string($id_or_name)
            ? static::whereLikeCi('name', $id_or_name)->first()
            : static::find($id_or_name);

        if (! $user) return null;

        return (object) [

            'id'          =>    $user?->id,
            'name'        =>    $user?->name,
            'email'       =>    $user?->email,
            'password'    =>    $user?->password,

        ];
    }

    // Copy from Person model (old) ************************************************************************************************************************************

    public static function getName(?int $id): ?string
    {
        return static::get($id)?->name;
    }

    public static function getId(?string $name): ?int
    {
        return static::get($name)?->id;
    }

    public static function getIdsByNames(?array $names): ?array
    {
        if (! $names) return null;
        return ($arr = static::whereInLike('name', $names)->pluck('id')->toArray()) === [] ? null : $arr;
    }

    public static function getNamesByIds(?array $ids): ?array
    {
        if (! $ids) return null;
        return ($arr = static::whereIn('id', $ids)->pluck('name')->toArray()) === [] ? null : $arr;
    }

    public static function changeName(int $id, string $name)
    {
        $possibleUser = static::get($name);
        if ($possibleUser) return null;

        return static::modify(
            id: $id,
            name: $name,
        );
    }

    /**
     * @return string[]|null
     */
    public static function getAll(): ?array
    {
        $arr =  static::query()->pluck('name')->toArray();
        return empty($arr) ? null : $arr;
    }


    /**
     * @return array<int, array<string, int>>|null
     */
    public static function getAllByNamesKey(): ?array
    {
        $arr = static::query()->pluck('id', 'name')->mapWithKeys( fn($id, $name) => [

            strtolower($name) => $id

        ])->toArray();

        return empty($arr) ? null : $arr;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function makeAdmin(): bool
    {
        return $this->forceFill(['is_admin' => true])->save();
    }

    public function removeAdmin(): bool
    {
        return $this->forceFill(['is_admin' => false])->save();
    }

    public function mustChangePassword(): void
    {
        $this->forceFill(['is_must_change_password' => true])->save();
    }

    public function isMustChangePassword(): bool
    {
        return $this->is_must_change_password;
    }

    public function passwordIsChanged(): bool
    {
        return $this->forceFill(['is_must_change_password' => false])->save();
    }

    public static function isAnyAdminSet(): bool
    {
        return static::where('is_admin', true)->exists();
    }

    public static function adminsCount(): int
    {
        return static::where('is_admin', true)->count();
    }
}
