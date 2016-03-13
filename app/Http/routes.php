<?php

//======================================================================
// ROUTES INDEPENDENT OF AUTH
//======================================================================
Route::group(['middleware' => ['web']], function () {
    Route::get('user/verify/{confirmcode}','Auth\AuthController@verifyEmail');
});
Route::group(['middleware' => ['web','guest']], function () {
    /**
    * Non-auth entrypoint routes
    */
    Route::get('/', [
      'as'=>'welcome',
      'uses' => 'Auth\AuthController@getWelcome'
    ]);

    Route::get('/about', function()
    {
        return view('noauth.about');
    });

    // Login registration routes
    Route::get('user/org/register', 'Auth\AuthController@getORegister');
    Route::get('user/res/register', 'Auth\AuthController@getRRegister');
    Route::post('user/register', 'Auth\AuthController@postRegister');
    Route::post('user/login', 'Auth\AuthController@login');

    // Password Reset Routes...
    Route::post('user/password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::get('user/password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('user/password/reset', 'Auth\PasswordController@reset');

    //Show status of vulnerabilities to general public
    Route::get('public/search/vulns','Universal\PublicController@getSearchform');
    Route::post('public/search/vulns',array('as'=>'vulnsjson','uses'=>'Universal\PublicController@getJVulnslist'));
    Route::get('public/product/{pid}','Universal\PublicController@getProductinfo');
    Route::get('public/vulnerability/{vid}','Universal\PublicController@getVulninfo');
});

//======================================================================
// ROUTES REQUIRE AUTH
//======================================================================
Route::group(['middleware' => ['web','auth']], function () {
    Route::get('user/logout/', [
      'as'=>'logout',
      'uses' => 'Auth\AuthController@logout'
    ]);
});

//======================================================================
// ROUTES REQUIRE AUTH -> BLOCKED STATUS CHECK -> APP
//======================================================================
Route::group(['middleware' => ['web','auth','blockcheck']], function () {
    /**
    * Authenticated users related common routes
    */
    Route::get('user/dashboard', [
      'as'=>'dashboard',
      'uses' => 'HomeController@index'
    ]);

    //Security settings related routes
    Route::get('user/settings/password', array('as'=>'password_change', 'uses' => 'Auth\AuthController@showPasschangeform'));
    Route::post('user/settings/password','Auth\AuthController@postAuthReset');

    //Profiles related routes
    Route::get('user/{username}',array('as'=>'viewprofile','uses'=>'Universal\ProfileController@view'));
    Route::get('user/profile/edit','Universal\ProfileController@editform');
    Route::post('user/profile/edit',array('as'=>'userprofile_update','uses'=>'Universal\ProfileController@saveProfileinfo'));

    //User notifications related routes
    Route::get('user/notifications/view','Universal\ProfileController@showNotifications');
    Route::get('user/notifications/mark/read','Universal\ProfileController@markNotifications');

    //User profile picture upload
    Route::post('user/profile/upload', ['as' => 'userprofilepic_upload', 'uses' => 'Universal\ProfileController@profpicupload']);

    // User file manager routes
    Route::get('user/files/download/{file_token}','Universal\FileController@getFile');

    // User search content routes
    Route::get('search/products','Universal\ProductController@getSearchform');
    Route::post('search/products',array('as'=>'productsjson','uses'=>'Universal\ProductController@getJProductslist'));
    Route::get('search/vulns','Universal\VulnController@getSearchform');
    Route::post('search/vulns',array('as'=>'vulnsjson','uses'=>'Universal\VulnController@getJVulnslist'));

    //View product information route
    Route::get('product/{pid}','Universal\ProductController@getProductinfo');

    //View vulnerabiltiy information route
    Route::get('vulnerability/{vid}','Universal\VulnController@getVulninfo');

    //User comments related routes
    Route::get('vuln/getcform/{vid}','Universal\CommentController@getCommentfileform');
    Route::post('vuln/comment/fileupload',array('as'=>'filewith_comment','uses'=>'Universal\CommentController@addCommentwfile'));
    Route::post('vuln/comment',array('as'=>'add_comment','uses'=>'Universal\CommentController@addComment'));
    Route::get('vuln/getcomments/{vid}','Universal\CommentController@getCommentsJson');

    //Vulnerability filter routes
    Route::get('user/vuln/tracker','Universal\VulnController@vulnTracker');
    Route::get('user/vulns/{type}','Universal\VulnController@listVulbytype');
});


//=============================================================================
// ROUTES REQUIRE AUTH -> BLOCKED STATUS CHECK -> ORG/VENDOR ROLE CHECK -> APP
//=============================================================================
Route::group(['middleware' => ['web','auth','blockcheck','orgprotect']], function () {

    //Product related routes
    Route::get('product/new/create','OrgController@createProduct');
    Route::get('product/edit/{pid}','OrgController@editProduct');
    Route::post('product/post/update',array('as'=>'product_create','uses'=>'OrgController@saveProduct'));
    Route::get('user/products','OrgController@listProducts');

    //Vendor update vulnerability status routes
    Route::get('mark/resolved/{vid}','Universal\VulnController@getMarkresolvedform');
    Route::post('mark/resolved',array('as'=>'markresolved','uses'=>'Universal\VulnController@postMarkresolved'));
});

//=============================================================================
// ROUTES REQUIRE AUTH -> BLOCKED STATUS CHECK -> RESEARCHER ROLE CHECK -> APP
//=============================================================================
Route::group(['middleware' => ['web','auth','blockcheck','resprotect']], function () {

    //Vulnerability related routes
    Route::get('vuln/create/{pid}','ResController@createVulnreport');
    Route::get('vuln/edit/{vid}','ResController@editVulnreport');
    Route::post('vuln/update',array('as'=>'vuln_update','uses'=>'ResController@saveVulnreport'));
});
