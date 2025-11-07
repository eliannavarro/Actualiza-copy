<?php

use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DataUserController;

Auth::routes();

Route::middleware(['auth', CheckRole::class . ':admin'])->group(function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // SIDEBAR
    Route::get('/sidebar-search', [DataController::class, 'sidebarSearch'])->name('sidebar.search');

    Route::resource('users', UserController::class);

    // IMPORTAR EXCEL
    Route::get('excel-importar', [DataController::class, 'showUploadForm'])->name('import.import');
    Route::post('excel/reemplazar', [DataController::class, 'replaceData'])->name('import.replace');
    // Route::post('excel/actualizar', [DataController::class, 'updateData'])->name('import.update');
    Route::post('excel/agregar', [DataController::class, 'addData'])->name('import.add');

    // ASIGNAR
    Route::get('/asignar', [DataController::class, 'asignarIndex'])->name('asignar.index');
    Route::get('/asignar-filtrar', [DataController::class, 'asignarFiltrar'])->name('asignar.filtrar');
    Route::post('/asignar-operario', [DataController::class, 'asignarOperario'])->name('asignar.operario');


    //DATA
    Route::delete('/visita/eliminar/{id}', [DataController::class, 'destroy'])->name('data.destroy');


    // DESASIGNAR
    Route::get('/desasignar', [DataController::class, 'desasignarIndex'])->name('desasignar.index');
    Route::get('/desasignar-filtrar', [DataController::class, 'desasignarFiltrar'])->name('desasignar.filtrar');
    Route::post('/desasignar-operario', [DataController::class, 'desasignarOperario'])->name('desasignar.operario');

    //USERDATA
    Route::get('/datauser/asignados', [DataUserController::class, 'index'])->name('datauser.asignados');
    Route::get('/datauser/completados', [DataUserController::class, 'completados'])->name('datauser.completados');
    Route::get('/asignados', [DataController::class, 'asignadosListar'])->name('asignados.index');
    Route::get('/asignados/edit/{data}', [DataController::class, 'asignadosEdit'])->name('asignados.edit');
    Route::put('/operario/update/{id}', [DataController::class, 'asignadosUpdate'])->name('asignados.update');
    
    //AGENDA
    Route::get('/agendar', [DataController::class, 'create'])->name('schedule.create'); // Formulario vacío (nuevo)
    Route::get('/agendar/{id}', [DataController::class, 'edit'])->name('schedule.edit'); // Formulario con datos existentes
    Route::post('/agendar', [DataController::class, 'store'])->name('schedule.store'); // Guardar nuevo
    Route::put('/agendar/{id}', [DataController::class, 'update'])->name('schedule.update'); // Actualizar existente

    //COMPLETADOS
    Route::get('/completados', [DataController::class, 'completadosIndex'])->name('completados.index');
    Route::get('/completados-filtrar', [DataController::class, 'completadosFiltrar'])->name('completados.filtrar');

    Route::get('/completados-editar/{id}', [DataController::class, 'editCompletados'])->name('completados.edit');
    Route::put('/completados-editar/{id}', [DataController::class, 'updateCompletados'])->name('completados.update');

    Route::delete('/completados-eliminar/{dataId}', [DataController::class, 'completadosDestroy'])->name('completados.destroy');

    //EXPORTAR EXCEL
     Route::get('/export-filtrar', [DataController::class, 'exportarFiltrar'])->name('export.filtrar');
    Route::get('/export', [DataController::class, 'exportarIndex'])->name('export');
    Route::get('/database/download', [DataController::class, 'download'])->name('database.download');
    Route::get('/export-data', [DataController::class, 'exportData'])->name('export.excel');
    Route::get('/export-data-complete', [DataController::class, 'exportDataComplete'])->name('export.excel.complete');
   
    
    //RUTAS PARA SERVICIOS
    Route::resource('servicio', ServicioController::class);
});


Route::middleware(['auth', CheckRole::class . ':user'])->group(function () {
    // Pendientes (asignados)
    Route::get('/datauser/asignados', [DataUserController::class, 'index'])->name('datauser.asignados');
 
    // odenes (usuarios normales)
    // Completados (usuarios normales)
    Route::get('/datauser/completados', [DataUserController::class, 'completados'])->name('datauser.completados');
    // pendientes (usuarios normales)
    Route::get('/datauser/pendientes', [DataUserController::class, 'asignadospendientes'])->name('datauser.pendientes');
    
    // Asignados (editar y actualizar)
    Route::get('/asignados', [DataController::class, 'asignadosListar'])->name('asignados.index');
    Route::get('/asignados/edit/{data}', [DataController::class, 'asignadosEdit'])->name('asignados.edit');
    Route::put('/operario/update/{id}', [DataController::class, 'asignadosUpdate'])->name('asignados.update');

    // Tickets
    Route::get('/ticket-options/{id}', [TicketController::class, 'showTicketOptions'])->name('ticket.options');
    Route::get('/ticket-generate/{id}', [TicketController::class, 'generateTicket'])->name('ticket.generate');
    Route::get('/ticket-download/{id}', [TicketController::class, 'downloadTicket'])->name('ticket.download');
});



Route::middleware('auth')->group(function () {
    //TICKETS 
    Route::get('/ticket-options/{id}', [TicketController::class, 'showTicketOptions'])->name('ticket.options');

    Route::get('/ticket-generate/{id}', [TicketController::class, 'generateTicket'])->name('ticket.generate');
    Route::get('/ticket-download/{id}', [TicketController::class, 'generateTicket'])->name('ticket.download');

    Route::get('/acta-generate/{id}', [TicketController::class, 'generateActa'])->name('acta.generate');

    Route::get('/remision-generate/{id}', [TicketController::class, 'generateRemision'])->name('remision.generate');
});
