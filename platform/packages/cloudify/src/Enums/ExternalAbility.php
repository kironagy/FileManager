<?php

namespace Botble\Cloudify\Enums;

use Botble\Base\Supports\Enum;

class ExternalAbility extends Enum
{
    public const LIST_MEDIA_FOLDERS = 'list_media_folders';

    public const CREATE_MEDIA_FOLDER = 'create_media_folder';

    public const EDIT_MEDIA_FOLDER = 'edit_media_folder';

    public const TRASH_MEDIA_FOLDER = 'trash_media_folder';

    public const DELETE_MEDIA_FOLDER = 'delete_media_folder';

    public const LIST_MEDIA_FILES = 'list_media_files';

    public const CREATE_MEDIA_FILE = 'create_media_file';

    public const EDIT_MEDIA_FILE = 'edit_media_file';

    public const TRASH_MEDIA_FILE = 'trash_media_file';

    public const DELETE_MEDIA_FILE = 'delete_media_file';

    protected static $langPath = 'packages/cloudify::cloudify.enums.external_ability';
}
