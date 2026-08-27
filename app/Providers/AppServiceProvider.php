<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Vite as FoundationVite;
use Illuminate\Foundation\ViteFonts as FoundationViteFonts;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->singleton(FoundationVite::class, fn() =>

            new class extends FoundationVite
            {

                /**
                 * Get the path to the manifest file for the given build directory.
                 *
                 * @param  string  $buildDirectory
                 * @return string
                 */
                protected function manifestPath($buildDirectory)
                {
                    // return public_path($buildDirectory.'/'.$this->manifestFilename);
                    return resource_path($this->manifestFilename);
                }

            }

        );

        $this->app->bind(FoundationViteFonts::class, fn() =>

            new class extends FoundationViteFonts
            {

                /**
                 * Read the font manifest for the given configuration.
                 *
                 * @param  bool  $isHot
                 * @param  string  $buildDirectory
                 * @param  string  $manifestFilename
                 * @param  string  $hotFile
                 * @return array<string, mixed>|null
                 *
                 * @throws \Illuminate\Foundation\ViteException
                 */
                public function manifest(bool $isHot, string $buildDirectory, string $manifestFilename, string $hotFile)
                {
                    $path = $isHot
                        ? dirname($hotFile).'/fonts-manifest.dev.json'
                        : resource_path($manifestFilename); // public_path($buildDirectory.'/'.$manifestFilename);

                    return $this->readManifest($path);
                }

            }

        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::useBuildDirectory('assets');
        Vite::useHotFile(resource_path('hot'));


        Gate::define('is-admin', fn(User $user) => $user->isAdmin());


        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return URL::temporarySignedRoute(
                'password.reset',
                now()->addMinutes((float) config('auth.passwords.users.expire', 1)),
                [
                    'token' => $token,
                    'email' => $user->getEmailForPasswordReset(),
                ],
            );
        });
    }

}
