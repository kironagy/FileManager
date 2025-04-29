<?php

namespace Botble\Cloudify\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Cloudify\Concerns\HasAbility;
use Botble\Cloudify\Enums\ExternalAbility;
use Botble\Cloudify\Http\Requests\StoreMediaFileRequest;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Http\Requests\MediaListRequest;
use Botble\Media\Http\Resources\FileResource;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MediaFileController extends BaseController
{
    use HasAbility;

    public function index(MediaListRequest $request, MediaFileInterface $fileRepository): BaseHttpResponse
    {
        $this->checkAbility(ExternalAbility::LIST_MEDIA_FILES);

        $files = FileResource::collection(
            $fileRepository->getFilesByFolderId($request->input('folder_id', 0))->where('is_folder', false)
        );

        return $this
            ->httpResponse()
            ->setData($files);
    }

    public function store(StoreMediaFileRequest $request): BaseHttpResponse
    {
        $this->checkAbility(ExternalAbility::CREATE_MEDIA_FILE);

        $file = RvMedia::handleUpload($request->file('file'));

        if ($file['error']) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($file['message']);
        }

        return $this
            ->httpResponse()
            ->setData(Arr::except($file['data'], ['icon']));
    }

    public function trash(string $file, MediaFileInterface $fileRepository): BaseHttpResponse
    {
        $this->checkAbility(ExternalAbility::TRASH_MEDIA_FILE);

        if (! $fileRepository->deleteBy(['id' => $file])) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('core/media::media.trash_error'));
        }

        return $this
            ->httpResponse()
            ->setMessage(trans('core/media::media.trash_success'));
    }

    public function destroy(string $file, MediaFileInterface $fileRepository): BaseHttpResponse
    {
        $this->checkAbility(ExternalAbility::DELETE_MEDIA_FILE);

        $fileRepository->forceDelete(['id' => $file]);

        return $this
            ->httpResponse()
            ->setMessage(trans('core/media::media.delete_success'));
    }

    public function deletes(Request $request, MediaFileInterface $fileRepository): BaseHttpResponse
    {
        $this->checkAbility(ExternalAbility::DELETE_MEDIA_FILE);

        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['required', 'string'],
        ]);

        foreach ($request->input('files') as $file) {
            $fileRepository->forceDelete(['id' => $file]);
        }

        return $this
            ->httpResponse()
            ->setMessage(trans('core/media::media.delete_success'));
    }
}
