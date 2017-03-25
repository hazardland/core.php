This is framework module. To see implementation of simple app using this framework please check https://github.com/hazardland/app.php

When you live in PHP world while developing various web Apps you are often required to have:
<!-- MarkdownTOC -->

- [Seo friendly nice URLs](#seo-friendly-nice-urls)
- [With simpliest routing in background](#with-simpliest-routing-in-background)
- [Defining locales, Always having active locale](#defining-locales-always-having-active-locale)
- [Generating custom urls](#generating-custom-urls)
- [Generating route action url for named route:](#generating-route-action-url-for-named-route)
- [Database handling, connet to server only when connection is required](#database-handling-connet-to-server-only-when-connection-is-required)
- [Abstract cache](#abstract-cache)
- [Dealing with sessions](#dealing-with-sessions)

<!-- /MarkdownTOC -->

## Seo friendly nice URLs

```php
en/about/
fr/about/
en/blog/123-cool-article
en/user/123
//see declaring routes for this links below
```

## With simpliest routing in background

```php
//this is for en/about and fr/about
//App::getLocale() determines current locale in this case 'fr' or 'en'
Route::add('about','MyAboutController@defaultMethod');

//for en/blog/123-cool-article
Route::add('blog/{post_id}-{*}', function($post_id){
    echo $post_id;
})->where('post_id',Input::INT);

//for en/user/123
Route::add('user/{id}', 'UserProfile@index');
```

Where ```UserProfile@index``` you can imagine as file at ```./app/src/Controller/UserProfile.php```

```php
//file: ./app/src/Controller/UserProfile.php
namespace App\Controller;
class UserProfile
{
    public function index ($user_id)
    {
        View::render('user/profile',['user_id'=>$user_id]);
    }
}
```

Or just if you fill lazy you might do not need controller at all just parse view in route declaration:

```php
//We had this
Route::add('user/{id}', 'UserProfile@index');

//We can this instead also:
Route::add('user/{id}', function($user_id)(
    View::render('user/profile',['user_id'=>$user_id]);
));

```

## Defining locales, Always having active locale

```php
//every day use
App::getLocale()
if (App::isLocale('en'))

//locale management
App::addLocale('en');
App::addLocale('fr');
App::setLocale('en');;
App::validLocale($locale);

//short version
if (App::locale()=='en')
if (App::locale('en'))


//path
App::getPath(); #get current app route path
```

## Generating custom urls

```php
App::url('blog/article/17');
//This will generate something like - en/blog/article/17
//With active locale (if defined any)
```

```php
//url
App::getUrl(); #will return - /en
//short version
App::url(); #alias of App:getUrl()
App::url('/my/custom/route'); #will return - /en/my/custom/route
//Note that if you have your project`s public folder at url:
// http://localhost/my/app/public
//App::url() will return - my/app/public/en

```

## Generating route action url for named route:

```php
Route:url('article',['id'=>17]);
//This will generate something like - en/blog/article/17
```
Where route name ```'article'``` was defined as follows:

```php
Route::add('blog/article/{id}', 'blog@article')->name('article');
```


## Database handling, connet to server only when connection is required
(And also hide connection setup from developers if needed)

```php
//config (u can have many connection definitions)
//this line does not open connection
Database::add (
    'mysql:host=127.0.0.1;dbname=test;charset=utf8', //dsn
    'root', //user
    '', //password
    [\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES=>false], //options
    'default' //name
);
//usage
//this line opens connection on first call
Database::get('mySecondConenction')->query("SELECT 1");
Database::setDefault('mySecondConnection');
Database::get()->query("SELECT 1");
```

## Abstract cache
(Some servers have APC some have APCu some of them even different caching engines) all you need is:

```php
//This line goes in config
Cache::init(new \Core\Cache\Driver\APCu());
//usage
Cache::set('key','value');
Cache::get('key');
Cache::exists('key');
Cache::remove('key');
Cache::clean();
```

## Dealing with sessions
There are things you need to maintain when opening session, sometimes you need to open session with custom id, sometimes you need to have session name to separate one app session from another app session, for this you can use session class:

```php
//this goes in config
Session::setName('myApp');
//this is called before ob_start
Session::open('abcdifjklmnopqrstuwxwz');
//basic usage
Session::set('key','value');
Session::get('key');
Session::all();
//this is to manage
Session::clean();
Session::close();
Session::getName();
Session::setId('abcdifjklmnopqrstuwxwz');
Session::getId();
Session::destroy();
```