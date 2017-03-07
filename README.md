When you live in PHP world while developing various web Apps you are often required to have:
1. Seo friendly nice urls

    ```
    en/about/
    fr/about/
    en/blog/123-cool-article
    en/user/123
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

4. Database handling, open connection only when needed, hide connection string from developers:

    ```php
        Database::add(new PDO(...),'mySecondConnection'); //hide this in config
        Database::get('mySecondConenction')->query("SELECT 1");
        Database::setDefault('mySecondConnection');
        Database::get()->query("SELECT 1");
    ```

5. Abstract cache (some servers have APC some have APCu some of them even different caching engines) all you need is:

    ```php
        Cache::set('key','value');
        Cache::get('key');
        Cache::exists('key');
        Cache::remove('key');
        Cache::clean();
    ```

6. Dealing with sessions, sometimes you dont want to open session ever, sometimes you want to open session by custom id, sometimes you have different session engines

    ```php
        Session::setName('myApp');
        Session::getName();
        Session::setId('abcdifjklmnopqrstuwxwz');
        Session::getId();
        Session::set('key','value');
        Session::get('key');
        Session::all();
        Session::clean();
        Session::open('abcdifjklmnopqrstuwxwz');
        Session::close();
        Session::destroy();
    ```
