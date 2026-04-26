<?php

namespace App\Support;

class WebResponse
{
    public function toResponse(ApiResponse $apiResponse, ?string $successfulRoute)
    {
        if (! $apiResponse->isSuccessful()) {
            return back()
                ->with('swal-error', $apiResponse->getMessage())
                ->withInput($apiResponse->getInput())
                ->withErrors($apiResponse->getErrors());
        }

        if ($successfulRoute === null) {
            return back()
                ->with('swal-success', $apiResponse->getMessage());
        }

        return redirect()
            ->to($successfulRoute)
            ->with('swal-success', $apiResponse->getMessage());
    }
}
