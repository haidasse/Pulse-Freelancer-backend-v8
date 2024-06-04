<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Passport::routes();

        $this->defineGates() ;
    }

    private function registerGates()
    {
        $permissions = [
            "attachments" => ["index", "show", "update", "updload", "donwload", "delete"],
            "banks" => ["index", "show", "create", "update", "delete"],
            "currencies" => ["index", "show", "create", "update", "delete"],
            "customers" => ["index", "show", "create", "update", "delete", "export", "import"],
            "customer_contacts" => ["index", "show", "create", "update", "delete"],
            "customer_groups" => ["index", "show", "create", "update", "delete"],
            "customer_addresses" => ["index", "show", "create", "update", "delete"],
            "custom_fields" => ["index", "show", "create", "update", "delete"],
            "estimates" => ["index"],
            "orders" => ["index"],
            "invoices" => ["index"],
            "documents" => ["index", "show", "create", "update", "delete", "export", "import"],
            "document_templates" => ["index", "show", "create", "update", "delete"],
            "document_statuses" => ["index", "show", "create", "update", "delete"],
            "document_lines" => ["index", "show", "create", "update", "delete"],
            "email_templates" => ["index", "show", "create", "update", "delete"],
            "expenses" => ["index", "show", "create", "update", "delete", "export", "import"],
            "expense_categories" => ["index", "show", "create", "update", "delete"],
            "items" => ["index", "show", "create", "update", "delete", "export"],
            "item_categories" => ["index", "show", "create", "update", "delete"],
            "notes" => ["index", "show", "create", "update", "delete"],
            "payments" => ["index", "show", "create", "update", "delete", "export", "import"],
            "payment_modes" => ["index", "show", "create", "update", "delete"],
            "roles" => ["index", "show", "create", "update", "delete"],
            "role_permissions" => ["index", "show", "create", "update", "delete"],
            "settings" => ["get", "set", "get_many", "set_many"],
            "taxes" => ["index", "show", "create", "update", "delete"],
            "users" => ["index", "show", "create", "update", "delete"],
            "vendors" => ["index", "show", "create", "update", "delete", "export", "import"],
            "numbers" => ["index", "show", "create", "update", "delete"],
            "reports" => ["sales", "expenses", "items", "payments", "taxes"]
        ] ;

        $gates = [] ;

        foreach ($permissions as $table => $actions) {
            foreach ($actions as $action) {
                $gates[] = $table.".".$action ;
            }
        }

        return $gates ;
    }
    private function defineGates()
    {
        $gates = $this->registerGates() ;

        foreach ($gates as $permission) {
            Gate::define($permission, function (User $user) use ($permission) {
                if ($user->role->is_administrator) {
                    return true ;
                }

                $permissions = $user->role->permissions->pluck("name")->toArray() ;

                if (!empty($permissions)) {
                    if (in_array($permission, $permissions)) {
                        return true ;
                    }
                }

                return false ;
            });
        }
    }
}