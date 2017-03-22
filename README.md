When you live in PHP world while developing various web Apps you are often required to have:
1. Seo friendly nice urls

    ```
    en/about/
    fr/about/
    en/blog/123-cool-article
    en/user/123
    //see declaring routes for this links below
    ```

    With simpliest routing in background:

    ```php
    //this is for en/about and fr/about
    //App::getLocale() determines current locale in this case 'fr' or 'en'
    Route::add('about','MyAboutController@defaultMethod');

    //for en/blog/123-cool-article
    Route::add('blog/{post_id}-{*}', function($post_id){
        echo $post_id;
    });

    //for en/user/123
    Route::add('user/{id}', 'UserProfile@index');
    ```

    Where ```UserProfile@index``` you can imagine as:
    file at ```./app/src/Controller/UserProfile.php```
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




2. Defining locales, Always having active locale

    ```php
        App::addLocale('en');
        App::addLocale('fr');
        App::setLocale('en');
        App::getLocale();
        App::isLocale($locale);
    ```

3. Define and work with locales

4. Database handling, connet to server only when connection is required, hide connection setup from developers:

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

5. Abstract cache (some servers have APC some have APCu some of them even different caching engines) all you need is:

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

6. Dealing with sessions, there are things you need to maintain when opening session, sometimes you need to open session with custom id, sometimes you need to have session name to separate one app session from another app session, for this you can use session class:

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
