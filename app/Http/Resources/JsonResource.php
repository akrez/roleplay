<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class JsonResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    public function toArray(?Request $request = null)
    {
        return (array) @json_decode(json_encode(parent::toArray($request ?? request())), true);
    }

    public function formatCarbonDateTime(?Carbon $date, string $format = 'Y-m-d H:i')
    {
        return $date ? [
            'en' => $date->format($format),
            'fa' => (new Verta($date->getTimestamp()))->format($format),
        ] : null;
    }

    public function formatEnum($enum)
    {
        return $enum ? [
            'name' => $enum->name,
            'trans' => $enum->trans(),
        ] : null;
    }
}
