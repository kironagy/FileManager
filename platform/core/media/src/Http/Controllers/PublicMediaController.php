<?php

namespace Botble\Media\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Models\MediaFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class PublicMediaController extends BaseController
{
    public function show(string $hash, string $id)
    {
        $originId = MediaFile::isUsingStringId() ? $id : hexdec($id);

        if (sha1($id) !== $hash) {
            abort(404);
        }

        $mediaFile = MediaFile::query()
            ->whereKey($originId)
            ->where('visibility', 'public')
            ->firstOrFail();

        $response = Http::get(
            RvMedia::url($mediaFile->url)
        );

        if ($response->ok()) {
            $body = $response->toPsrResponse()->getBody();

            return Response::streamDownload(function () use ($body) {
                while (! $body->eof()) {
                    echo $body->read(1024);
                }
            }, headers: $response->headers());
        }

        abort(403);
    }
}
