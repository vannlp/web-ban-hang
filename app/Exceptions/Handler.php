<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            // Nếu request là API hoặc muốn JSON
        
            // ✅ Nếu là Inertia request thì để Laravel xử lý bình thường
            if ($request->header('X-Inertia')) {
                return redirect()->back()
                    ->withErrors($exception->errors())
                    ->withInput();
            }
            
            if ($request->expectsJson() || $request->isJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $exception->errors(),
                ], 422);
            }

            // Nếu là Inertia hoặc web thì để Laravel xử lý như mặc định
        }

        return parent::render($request, $exception);
    }
}
