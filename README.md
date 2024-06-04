# **InvoiceFit Documentation**

Laravel documentation :

https://laravel.com/docs/8.x  
https://laravel-guide.readthedocs.io/en/latest/  
https://github.com/alexeymezenin/laravel-best-practices

Standard Laravel tools accepted by community :

https://github.com/alexeymezenin/laravel-best-practices

Syntax Markdown:

https://guides.github.com/features/mastering-markdown/

# Install

-   Install VsCode extentions in /.vscode/extensions.json
-   Créer un fichier .env et copier dedans ce qui y avait dans le fichier .env.example
-   Mettre les accès de votre BDD
-   Executer les commandes

```
composer install
php artisan migrate:fresh --seed
php artisan key:generate
php artisan passport:install
php artisan passport:client --personal
php artisan serve
```

# DoD : Definition of Done

A faire avant la réunion, max à 14h

1. git commit + git push dans la branche du dev
2. php artisan migrate:fresh --seed
3. php artisan route:list
4. php artisan serve
5. php artisan test
6. ajouter le nécessaire dans readme
7. ajouter la documentation du code
8. ajouter les tests fonctionnels


## Database

Schema is in \docs\datatable.mwb. You can open it using MysqlWorkbench

https://dev.mysql.com/downloads/workbench/


# Laravel Telescope

https://laravel.com/docs/8.x/telescope

Laravel Telescope is an elegant debug assistant for the Laravel framework. Telescope provides insight into the requests coming into your application, exceptions, log entries, database queries, queued jobs, mail, notifications, cache operations, scheduled tasks, variable dumps and more. Telescope makes a wonderful companion to your local Laravel development environment.

```
composer require laravel/telescope
php artisan telescope:install
php artisan migrate
```

http://127.0.0.1:8000/telescope/

Publishing Migrations

```
php artisan vendor:publish --tag=telescope-migrations
```

# Migration

You can create new migrations with :

```
php artisan make:migration create_customers_table
```

# Routes

Factories are located in \_app\routes\api\_

https://laravel.com/docs/8.x/routing

Les requêtes sont directemet intercepté par le
RouteServiceProvider dans cette class on a definit dans la fonction boot la posibilité de scanner toutes les routes qui exisent dans routes/api/.php ayant l'extension .php comme des fichiers séparer contenant la route pour chaque controlleur, les routes onété definit grace a rousource qui nous génére les methodes pour chaque route .

```
foreach (glob('routes/api/\*.php') as $file)
{
    Route::prefix('api')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group($file);
    Route::pattern('id', '[0-9]+')
}
```

Nomage des Controlleurs : Pascal Case en singulier. Ex : CustomerContactController

End Point Paths : Camel Case en pluriel. Exemple : customerContacts

Exemple du fichier route attachement.php

```
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BankController;

Route::apiResource('banks', BankController::class);
```

**Methodes du CRUD**

-   GET: http://127.0.0.1:8000/api/banks
-   POST: http://127.0.0.1:8000/api/banks
-   PUT: http://127.0.0.1:8000/api/banks/5
-   DELETE: http://127.0.0.1:8000/api/banks/5
-   GET: http://127.0.0.1:8000/api/banks/5

```
php artisan route:list
```

# Factories

https://laravel.com/docs/8.x/releases#model-factory-classes

We use Faker for generating dummy text :  
https://fakerphp.github.io/formatters/text-and-paragraphs/#text  
https://github.com/fzaninotto/Faker

Factories are located in _app\database\factories_

You can generate a new Factory with :

```
php artisan make:factory Bank
```

Then in the definition method put your code :

```
public function definition()
{
    return [
        'name' => $this->faker->name,
        'currency' => $this->faker->currencyCode(),
        'swift' => $this->faker->swiftBicNumber(),
        'iban' => $this->faker->iban()
    ];
}
```

For relationship use :

```
return [
    'customer_id' => Customer::inRandomOrder()->first()->id,
]
```

# Seeders

https://laravel.com/docs/8.x/seeding

Seeders are located in _app\database\seeders_

You can generate a new Factory with :

```
php artisan make:seeder BankSeeder
```

-   First DatabaseSeeder is called :

```
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class
         ]);
    }
}

```

-   Then specify what each seeder does :

```
class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->times(10)->create();
    }
}
```

# Laravel Passport

https://laravel.com/docs/8.x/passport

Laravel Passport provides a full OAuth2 server implementation for your Laravel application in a matter of minutes. Passport is built on top of the League OAuth2 server that is maintained by Andy Millington and Simon Hamp.

```
composer require laravel/passport
php artisan migrate:fresh --seed
php artisan passport:install
php artisan serve
```

# Laravel Socialite

https://laravel.com/docs/8.x/socialite

The Laravel socialite package makes authentification with traditional social networks simple. So download the socialite package just running the below command.

```
composer require laravel/ui
composer require laravel/socialite
php artisan ui bootstrap --auth
npm install
npm run dev
```

## Update Database Credentials

After that update your .env file which is located in your project root.

```
GOOGLE_CLIENT_ID="your google id"
GOOGLE_CLIENT_SECRET="your secret"
```

Now you have to set app id, secret and call back url in config file so open config/services.php and set id and secret this way:
config/services.php

```
return [
    ....
    'google' => [
        'client_id' => 'app id',
        'client_secret' => 'add secret',
        'redirect' => 'http://localhost:8000/auth/google/callback',
    ],
]
```

After install above package we should add providers and aliases in config file, Now open config/app.php file and add service provider and aliase.

```
'providers' => [
    ....
    Laravel\Socialite\SocialiteServiceProvider::class,

],
'aliases' => [
    ....
    'Socialite' => Laravel\Socialite\Facades\Socialite::class,

],
```

## Get secrets from Google

Here we get CLIENT ID and CLIENT SECRET for the google login button in Laravel based project, So we need to go https://console.developers.google.com/ and create an app. Create an app in the config/service.php file.
Now our App settings are done, copy your client id, client secret, and predict you're and save in .env file.

# API Documentation

Utiliser la norme OpenAPI pour documenter chaque controller.

# Tests Unitaires

Utiliser PhpUnit pour écrire le test unitaire pour chaque controller.

You can also take a look at this URL to search for all Formatters:

https://fakerphp.github.io/formatters/

# Controllers

The controllers are files that send data to the views. To create a controller, we can use the command line:

```
php artisan make:controller ControllerName
```

# Crud

CRUD is an acronym for: CREATE. READ. UPDATE. DELETE. They are the methods used by the controller to persist data.

**CRUD example**

```
public function index(Request $params)
{
    return $this->model::all();
}

public function show($id)
{
    return $this->model::findOrFail($id);
}

public function store(Request $request)
{
    $model = new $this->model();
    $model->create($request->all());
    return $model;
}

public function update(Request $request, $id)
{
    $model = $this->model::find($id);
    $model->update($request->all());
    return $model;
}

public function destroy($id)
{
    $model = $this->model::find($id);
    return $model->delete();
}
```

# Traits

Traits are a mechanism for code reuse in single inheritance languages such as PHP. A Trait is intended to reduce some limitations of single inheritance by enabling a developer to reuse sets of methods freely in several independent classes living in different class hierarchies. To create a trait we can use the command line:

```
php artisan make:trait HasTraitName
```

To use the trait, we simply add this line in the Model.

```
use HasTraitName
```

# Exceptions

https://laravel.com/docs/8.x/errors  
https://www.php.net/manual/fr/language.exceptions.extending.php  
https://openclassrooms.com/fr/courses/1665806-programmez-en-oriente-objet-en-php/1667289-les-exceptions

Exceptions are located in app\Exceptions

## 1. ModelNotFoundException

Cette exception return "Model"/"id" not exist avec status 500.

La methode qui nous permet de retourner le message d'exeption dans la class ModelNotFoundException:

## 2. ModelNotValidException

Cette exception return les erreurs de validation avec status 500.

La methode qui nous permet de retourner le message d'exception dans la class ModelNotValiException:

On utlise cette exception lors de la validation des données avant de les inserer dans a base de données.

On peut créé une fonction validator dans un controller ou un trait et on appele le'xeption par exemple:

## 3. NotFoundHttpException

Cette exception retourne message "Not Found" avec status 404.

On a personnaliser cette exception dans la fonction register de la class handler.

## 4. MethodNotAllowedHttpException

Cette exception retourne message "The "current method" method is not supported for this route." avec status 405.

On a personnaliser cette exception dans la fonction register de la class handler.

## 5. MethodNeedsAuthentification

Cette exception retourne message "this method need authentification" avec status 401.

La methode qui nous permet de retourner le message d'exeption dans la class MethodNeedsAuthentification:

## 6. InvalidTokenException

Cette exception retourne message "invalid token" avec status 403.

La methode qui nous permet de retourner le message d'exeption dans la class InvalidTokenException:

# **Scopes**

https://laravel.com/docs/8.x/eloquent#local-scopes

A scope is a method in the model that makes it able to add database logic into your model.

```
php artisan make:scope ScopeName
```

### **Scope example**

```
public function scopeSort(Builder $query, Request $request)
{

}
```

### **Sort Scope**

The Sort scope allows us to sort data in a specific order by passing attributes into the query. By default, the order is ascendent. By addind "-" before the attribute, the order is descendent.
In orther for this to work, the attributes must be sortable. This means that they must be declared in the model as \$sortable.

### **Columns Scope**

The Columns scope allows us to show data from specific attributes. Like the Sort scope, attributes must be passed in the query.

# POSTMAN

https://www.postman.com/

Postman is a great tool when trying to dissect RESTful APIs made by others or test ones you have made yourself. It offers a sleek user interface with which to make HTML requests, without the hassle of writing a bunch of code just to test an API's functionality.

# Event Logs

You can log event anywhere using this :

```
EventLog::log([
    "object_type" => app(get_class($model))->getTable() ,
    "object_id" => $model->id,
    "action" => $action,
    "attributes" => $model->attributes,
    "original" => $model->original,
    "changes" => $model->changes,
]) ;
```

# Mailtrap

-   Url : https://mailtrap.io/signin
-   Username : ylemrini@gmail.com
-   Password : 7NQxtCi7qrMxzQ7

-   MAIL_MAILER=smtp
-   MAIL_HOST=smtp.mailtrap.io
-   MAIL_PORT=25
-   MAIL_USERNAME=fd6c1c2e0e5695
-   MAIL_PASSWORD=61e7d08e7ad042
-   MAIL_ENCRYPTION=null
-   MAIL_FROM_ADDRESS=ylemrini@gmail.com
-   MAIL_FROM_NAME="${APP_NAME}"

# PhpUnit

https://laravel.com/docs/8.x/http-tests#available-assertions

```
php artisan migrate:fresh --seed --env=testing
php artisan test
php artisan test --filter NumberTest
php artisan test --filter NumberTest::test_index
```

Generating report coverage :

```
php artisan test --coverage-html tests/Report
```

Generating report.xml

https://marmelab.com/phpunit-d3-report/

```
php artisan test --log-junit report.xml

```

# OpenApi Documentation

https://github.com/DarkaOnLine/L5-Swagger  
https://zircote.github.io/swagger-php/  
https://swagger.io/docs/specification/basic-structure/

http://127.0.0.1:8000/api/documentation

```
php artisan l5-swagger:generate

```

# Gates

https://laravel.com/docs/8.x/authorization

All Controllers are defined in :`\storage\gates\gates.json`

Gates are defined in `app\Providers\AuthServiceProvider.php`

You have 3 ways to use Gates :

1. in Controllers

```
$this->authorize($permission, Auth::user());
```

2. in MiddleWares

```
Route::get('/settings/many', 'Api\SettingController@getMany')
    ->middleware('can:get-many-settings')
    ->name('settings.get-many');
```

3. in Models

```
if (! Gate::allows($permission, Auth::user()) {
    abort(403);
}
if (! Gate::denies($permission, Auth::user())
if (! Gate::any($permission, Auth::user())
if (! Gate::none($permission, Auth::user())
```

You can check all gates with Tinker :

```
php artisan tinker
Gate::abilities()
```

# File Storage

https://laravel.com/docs/8.x/filesystem

### **Example of file Uploads in Laravel**

```
public function upload(Request $request)
{
    $path = $request->file('file')->store('files');

}
```

### **Example of file Dwonloads in Laravel**

```
Storage::download('filename.txt');
```

### **Example of file Deletes in Laravel**

```
Storage::delete('filename.txt');
```


# Imports & Exports 

https://docs.laravel-excel.com/3.1/imports/basics.html 

Laravel Excel is intended at being Laravel-flavoured PhpSpreadsheet: a simple, but elegant wrapper around PhpSpreadsheet with the goal of simplifying exports and imports.

# Datatables 

filter: 

- string
- numeric
- date 
- list : 'multiple' | 'tags' | 'default'
- autocomplete
- boolean

