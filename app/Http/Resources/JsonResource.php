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

    public function formatCarbonDateTime(?Carbon $date)
    {
        return $date ? [
            'en' => $date->format('Y-m-d H:i:s'),
            'fa' => (new Verta($date->getTimestamp()))->format('Y-m-d H:i:s'),
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
