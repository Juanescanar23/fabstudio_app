<?php

use App\Http\Controllers\Portal\ClientPortalController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth'])->prefix('portal')->name('portal.')->controller(ClientPortalController::class)->group(function () {
    Route::get('/', 'index')->name('dashboard');
    Route::get('/projects/{project}', 'showProject')->name('projects.show');
    Route::get('/projects/{project}/documents', 'documents')->name('projects.documents');
    Route::get('/projects/{project}/documents/{version}/download', 'downloadDocument')->name('projects.documents.download');
    Route::get('/projects/{project}/visuals', 'visuals')->name('projects.visuals');
    Route::get('/projects/{project}/visuals/{visualAsset}/file', 'visualAssetFile')->name('projects.visuals.file');
    Route::post('/projects/{project}/comments', 'storeComment')->name('projects.comments.store');
    Route::post('/projects/{project}/decisions', 'storeDecision')->name('projects.decisions.store');
});

require __DIR__.'/settings.php';
