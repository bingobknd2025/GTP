<?php

namespace App\Providers;

use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        app('router')->aliasMiddleware('role', \Spatie\Permission\Middlewares\RoleMiddleware::class);
        app('router')->aliasMiddleware('permission', \Spatie\Permission\Middlewares\PermissionMiddleware::class);
        app('router')->aliasMiddleware('role_or_permission', \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class);

        // try {
        //     $settings = DB::table('settings')->first();

        //     if ($settings) {
        //         config([
        //             'mail.mailers.smtp.host'       => $settings->smtp_host,
        //             'mail.mailers.smtp.port'       => $settings->smtp_port,
        //             'mail.mailers.smtp.encryption' => $settings->smtp_encryption,
        //             'mail.mailers.smtp.username'   => $settings->smtp_username,
        //             'mail.mailers.smtp.password'   => $settings->smtp_password,
        //             'mail.from.address'            => $settings->mail_from_email,
        //             'mail.from.name'               => $settings->mail_from_name,
        //         ]);
        //     }
        // } catch (\Exception $e) {
        //     return;
        // }
    }
}
