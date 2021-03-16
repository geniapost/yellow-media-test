<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api\v1',
                'prefix' => 'api',
                'as' => 'api.'],
                    function (){
                        Route::group(['prefix' => 'user',
                                      'as' => 'user.'],
                        function (){
                            Route::post('register', 'UserController@register');
                            Route::post('authenticate', 'UserController@authenticate');
                            Route::group(['middleware' => 'auth:api'], function (){
                                Route::post('recover-password','UserController@recoverPassword');
                                Route::get('companies','CompanyController@show');
                                Route::post('companies','CompanyController@store');
                            });
                        });
            },
);
