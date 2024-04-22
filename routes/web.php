<?php

use App\Enums\UserType;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
 * OK so for "reasons", route redirects bottom up
 */

Route::view('/', 'welcome');

/*
 * URI is now system/userType due to how having it as a top level URI means that the form takes forever to load
 */
Route::get('/system/{userType}', function (UserType $userType) {
})
    ->middleware(['auth', 'verified'])
    ->name('redirectUser');

require __DIR__ . '/auth.php';
