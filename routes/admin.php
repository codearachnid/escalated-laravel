<?php

use Escalated\Laravel\Http\Controllers\AdminCannedResponseController;
use Escalated\Laravel\Http\Controllers\AdminDepartmentController;
use Escalated\Laravel\Http\Controllers\AdminEscalationRuleController;
use Escalated\Laravel\Http\Controllers\AdminReportController;
use Escalated\Laravel\Http\Controllers\AdminSettingsController;
use Escalated\Laravel\Http\Controllers\AdminSlaPolicyController;
use Escalated\Laravel\Http\Controllers\AdminTagController;
use Escalated\Laravel\Http\Controllers\AdminTicketController;
use Escalated\Laravel\Http\Middleware\EnsureIsAdmin;
use Escalated\Laravel\Http\Middleware\ResolveTicketByReference;
use Illuminate\Support\Facades\Route;

Route::middleware(array_merge(config('escalated.routes.admin_middleware', ['web', 'auth']), [EnsureIsAdmin::class]))
    ->prefix(config('escalated.routes.prefix', 'support').'/admin')
    ->group(function () {
        Route::get('/reports', AdminReportController::class)->name('escalated.admin.reports');

        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('escalated.admin.tickets.index');

        Route::middleware(ResolveTicketByReference::class)->group(function () {
            Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('escalated.admin.tickets.show');
            Route::post('/tickets/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('escalated.admin.tickets.reply');
            Route::post('/tickets/{ticket}/note', [AdminTicketController::class, 'note'])->name('escalated.admin.tickets.note');
            Route::post('/tickets/{ticket}/assign', [AdminTicketController::class, 'assign'])->name('escalated.admin.tickets.assign');
            Route::post('/tickets/{ticket}/status', [AdminTicketController::class, 'status'])->name('escalated.admin.tickets.status');
            Route::post('/tickets/{ticket}/priority', [AdminTicketController::class, 'priority'])->name('escalated.admin.tickets.priority');
            Route::post('/tickets/{ticket}/tags', [AdminTicketController::class, 'tags'])->name('escalated.admin.tickets.tags');
            Route::post('/tickets/{ticket}/department', [AdminTicketController::class, 'department'])->name('escalated.admin.tickets.department');
        });

        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('escalated.admin.settings');
        Route::post('/settings', [AdminSettingsController::class, 'update'])->name('escalated.admin.settings.update');

        Route::resource('departments', AdminDepartmentController::class)
            ->names('escalated.admin.departments')
            ->except(['show']);

        Route::resource('sla-policies', AdminSlaPolicyController::class)
            ->names('escalated.admin.sla-policies')
            ->except(['show']);

        Route::resource('escalation-rules', AdminEscalationRuleController::class)
            ->names('escalated.admin.escalation-rules')
            ->except(['show']);

        Route::get('/tags', [AdminTagController::class, 'index'])->name('escalated.admin.tags.index');
        Route::post('/tags', [AdminTagController::class, 'store'])->name('escalated.admin.tags.store');
        Route::put('/tags/{tag}', [AdminTagController::class, 'update'])->name('escalated.admin.tags.update');
        Route::delete('/tags/{tag}', [AdminTagController::class, 'destroy'])->name('escalated.admin.tags.destroy');

        Route::get('/canned-responses', [AdminCannedResponseController::class, 'index'])->name('escalated.admin.canned-responses.index');
        Route::post('/canned-responses', [AdminCannedResponseController::class, 'store'])->name('escalated.admin.canned-responses.store');
        Route::put('/canned-responses/{cannedResponse}', [AdminCannedResponseController::class, 'update'])->name('escalated.admin.canned-responses.update');
        Route::delete('/canned-responses/{cannedResponse}', [AdminCannedResponseController::class, 'destroy'])->name('escalated.admin.canned-responses.destroy');
    });
