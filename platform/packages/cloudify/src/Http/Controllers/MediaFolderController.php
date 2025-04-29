<?php

namespace Botble\Cloudify\Http\Controllers;

use Botble\ACL\Models\User;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Cloudify\Concerns\HasAbility;
use Botble\Cloudify\Enums\ExternalAbility;
use Botble\Media\Http\Requests\MediaFolderRequest;
use Botble\Media\Http\Requests\MediaListRequest;
use Botble\Media\Http\Resources\FolderResource;
use Botble\Media\Models\MediaFolder;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Illuminate\Http\Request;

class MediaFolderController extends BaseController
{
    use HasAbility;

    public function index(MediaListRequest $request, MediaFileInterface $fileRepository): BaseHttpResponse
    {
        $this->checkAbility(ExternalAbility::LIST_MEDIA_FOLDERS);

        $folders = FolderResource::collection(
            $fileRepository->getFilesByFolderId($request->input('folder_id', 0))->where('is_folder', true)
        );

        return $this
            ->httpResponse()
            ->setData($folders);
    }

    public function store(MediaFolderRequest $request): BaseHttpResponse
    {
        $this->checkAbility(ExternalAbility::CREATE_MEDIA_FOLDER);

        $name = $request->input('name');
        $parentId = $request->input('parent_id', 0);

        $mediaFolder = MediaFolder::query()->create([
            ...$request->validated(),
            'name' => MediaFolder::createName($name, $parentId),
            'slug' => MediaFolder::createSlug($name, $parentId),
            'parent_id' => $parentId,
            'user_id' => User::query()->first()->getKey(),
        ]);

        return $this
            ->httpResponse()
            ->setData(new FolderResource($mediaFolder))
            ->setMessage(trans('core/media::media.folder_created'));
    }

    public function trash(string $folder, MediaFolderInterface $folderRepository): BaseHttpResponse
    {
        $this->checkAbility(ExternalAbility::TRASH_MEDIA_FOLDER);

        $folderRepository->deleteFolder($folder);

        return $this
            ->httpResponse()
            ->setMessage(trans('core/media::media.trash_success'));
    }

    public function destroy(string $folder, MediaFolderInterface $folderRepository): BaseHttpResponse
    {
        $this->checkAbility(ExternalAbility::DELETE_MEDIA_FOLDER);

        $folderRepository->deleteFolder($folder, true);

        return $this
            ->httpResponse()
            ->setMessage(trans('core/media::media.delete_success'));
    }

    public function deletes(Request $request, MediaFolderInterface $folderRepository): BaseHttpResponse
    {
        $this->checkAbility(ExternalAbility::DELETE_MEDIA_FOLDER);

        $request->validate([
            'folders' => ['required', 'array'],
            'folders.*' => ['required', 'string'],
        ]);

        foreach ($request->input('folders') as $folder) {
            $folderRepository->deleteFolder($folder, true);
        }

        return $this
            ->httpResponse()
            ->setMessage(trans('core/media::media.delete_success'));
    }
}
