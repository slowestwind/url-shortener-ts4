<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function generate(ShortLink $link)
    {
        $this->authorize('view', $link);

        return QrCode::size(300)
            ->format('png')
            ->generate($link->getPublicUrl());
    }

    public function download(ShortLink $link)
    {
        $this->authorize('view', $link);

        $qrCode = QrCode::size(300)
            ->format('png')
            ->generate($link->getPublicUrl());

        return response($qrCode, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => "attachment; filename=\"{$link->slug}-qr.png\"",
        ]);
    }
}
