<?php

namespace App\Exceptions;

use Inertia\Inertia;
use Illuminate\Support\Facades\App;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // $this->renderable(function (ProjectsException $e, $request) {
        //     return redirect(route('projects.index'));
        // });
    }

    public function render($request, \Throwable $e)
    {
        $response = parent::render($request, $e);
        
        if ($e instanceof AuthorizationException) {
            return redirect(route('projects.index'));
        }
            // dd($exception);
        if ($response->status() === 419) {
            return back()->with(
                [
                'message' => 'The page expired, please try again.',
                    ]
            );
        }
        
        return $response;
    }
}
