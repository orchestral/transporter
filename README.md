Migration Transporter
=====================

Have you ever taken a project that had a messy, unstructured database design? Have you ever wish you can transform those project to become more Eloquent friendly?

```php
use App\User;
use Illuminate\Database\Query\Builder;
use Orchestra\Transporter\Blueprint;
use Orchestra\Transporter\Schema;

Schema::table('member', function (Blueprint $blueprint) {
    $blueprint->connection('legacy')
        ->key('member_id')
        ->filter(function (Builder $query) {
            $query->where('active', '=', 1);
        })->transport(function ($data) {
            $user = new User([
                'email' => $data->u_email,
                'password' => $data->u_password,
            ]);

            $user->save();

            return $user->getKey();
        });
})->start();
```
