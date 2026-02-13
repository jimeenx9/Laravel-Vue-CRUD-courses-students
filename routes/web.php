<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPA catch-all
|--------------------------------------------------------------------------
| Laravel siempre devuelve la vista principal y Vue Router decide la página
*/

Route::view('/{any}', 'welcome')->where('any', '.*');
