<?php

use App\Http\Controllers\Api\ClienteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/clientes', ClienteController::class);
Route::get('/professions',[ClienteController::class,'getProfessions'])->name('clientes.profissoes');
Route::post('/clientes-search', [ClienteController::class, 'searchClient'])->name('clientes.pesquisa');