<?php

use App\Http\Controllers\Api\ProjectsController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\ArticlesController;
use App\Http\Controllers\Api\ContactUsController;
use Illuminate\Support\Facades\Route;

Route::get('projects',[ProjectsController::class,'index']);
Route::get('projects/{project:slug}',[ProjectsController::class,'show']);
Route::get('related-projects/{project:slug}',[ProjectsController::class,'relatedProjects']);
Route::post('image-upload',[ProjectsController::class,'storeImage']);

Route::get('settings',[SettingsController::class,'index']);

Route::post('contact-us',[ContactUsController::class,'store']);