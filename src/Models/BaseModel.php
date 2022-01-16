<?php
/*
 *
  ____                 _ _     _____           _                       _
 |  _ \               | | |   |_   _|         | |                     (_)
 | |_) | ___ _ __   __| | |_    | |  _ __   __| | ___  _ __   ___  ___ _  __ _
 |  _ < / _ \ '_ \ / _` | __|   | | | '_ \ / _` |/ _ \| '_ \ / _ \/ __| |/ _` |
 | |_) |  __/ | | | (_| | |_   _| |_| | | | (_| | (_) | | | |  __/\__ \ | (_| |
 |____/ \___|_| |_|\__,_|\__| |_____|_| |_|\__,_|\___/|_| |_|\___||___/_|\__,_|

 Please don't modify this file because it may be overwritten when re-generated.
 Last Update 13 Sep 2020
 */

namespace Bendt\Report\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

abstract class BaseModel extends Model
{
    protected $table = 'base_model';

    protected $guarded = [];

    protected $processed = []; // ['slug', 'index']

    protected $files = [];

    protected $file_prefix = '';

    //index yang akan digunakan untuk nama file yang akan disimpan
    const FILE_NAME_IDX = 'name';

    const ORIGINAL_FILE_NAME = false;

    const FILE_PATH = '/base_model/';

    const CACHE_KEYS = [];

    const INDEX_KEY = 'sort_no';

    const SLUG_KEY = 'slug';

    const SLUG_FROM_COLUMN = 'name';


    public function process($files = null, $request_type = 'update')
    {
        DB::beginTransaction();
        if (isset($this->processed)) {
            static::processSlug($request_type);
            static::processSortNo();
        }
        static::processFile($files);

        $attributes = (array) $this->attributes;
        if ($request_type === 'update' && Auth::user() && array_key_exists('updated_by_id',$attributes)) {
            $this->updated_by_id = Auth::user()->id;
        }

        $traits = class_uses(new $this);
        if($request_type === 'create' && Auth::user()) {
            if(in_array('App\Traits\BelongsToCreatedByTrait',$traits)) {
                $this->created_by_id = Auth::user()->id;
            }

            if(in_array('App\Traits\BelongsToUpdatedByTrait',$traits)) {
                $this->updated_by_id = Auth::user()->id;
            }
        }

        $this->save();
        DB::commit();
        static::ClearCache();
    }

    public function processFile($files = null)
    {
        if ($files && count($files) > 0) {
            foreach ($files as $index => $file) {
                if (in_array($index, $this->files)) {
                    $old_file = $this->{$index};
                    $file_name = $this->setFileName($file, $index);
                    $this->saveFile($file, $file_name);
                    if ($old_file) delete_file($old_file);
                }
            }
        }
    }

    public function processSlug($type = 'update')
    {
        if ($type == 'create' && in_array('slug', $this->processed)) {
            $this->setSlug();
        }
    }

    public function processSortNo()
    {
        if (!$this->id
            && ( in_array('index', $this->processed) || in_array('sort_no', $this->processed) )
            && is_null($this->{static::INDEX_KEY})) {
            $this->setIndex();
        }
    }

    public function remove()
    {
        try {
            DB::beginTransaction();

            if (count($this->files) > 0) {
                foreach ($this->files as $index => $file) {
                    if (isset($this->{$file})) {
                        delete_file($this->{$file});
                    }
                }
            }

            $attributes = (array) $this->attributes;
            if (array_key_exists('deleted_by_id',$attributes) && Auth::user()) {
                $this->deleted_by_id = Auth::user()->id;
                $this->save();
            }

            $this->delete();

            DB::commit();
        } catch (\Exception $ex) {
            return false;
        }

        static::ClearCache();
    }

    // Image (Editable)
    public function setFileName($file, $key)
    {
        $file_name = $this->file_prefix;
        if(static::ORIGINAL_FILE_NAME) {
            $file_name .= $file->getClientOriginalName();
        } else {
            $file_name .= Str::slug(static::FILE_NAME_IDX);
        }
        $file_name .= '-' . (microtime(true)*10000) . '.' . $file->getClientOriginalExtension();
        $this->{$key} = static::FILE_PATH . $file_name;
        return $file_name;
    }

    public function saveFile($image, $image_name)
    {
        save_file(static::FILE_PATH, $image, $image_name);
    }

    // Slug
    public function setSlug()
    {
        $slug = Str::slug($this->{static::SLUG_FROM_COLUMN});
        $this->{static::SLUG_KEY} = $slug.static::_checkSlug($slug);
    }

    //Prefix
    public function setPrefix($prefix)
    {
        $this->file_prefix = $prefix;
    }

    // Index
    public function setIndex()
    {
        $index = static::_getLatestIndex();
        $this->{static::INDEX_KEY} = $index + 1;
    }

    public function move($new_index)
    {
        $list = static::where(static::INDEX_KEY, '>=', $new_index)->where('id', '!=', $this->id)->orderBy(static::INDEX_KEY)->get();

        DB::beginTransaction();
        $this->{static::INDEX_KEY} = $new_index;
        $this->save();
        for ($i = 0; $i < count($list); $i++) {
            $temp = $list[$i];
            $temp->{static::INDEX_KEY} = $new_index + $i + 1;
            $temp->save();
        }
        DB::commit();
        static::ClearCache();
    }

    public function moveByTargetId($target_id)
    {
        $target = static::find($target_id);
        $new_index = $target->{static::INDEX_KEY};

        $list = static::where(static::INDEX_KEY, '>', $new_index)->where('id', '!=', $this->id)->orderBy(static::INDEX_KEY)->get();

        DB::beginTransaction();
        if (count($list) == 0) {
            $this->{static::INDEX_KEY} = $new_index + 1;
            $this->save();
        } else {
            $this->{static::INDEX_KEY} = $new_index;
            $this->save();
            $target->{static::INDEX_KEY} = $new_index + 1;
            $target->save();

            for ($i = 0; $i < count($list); $i++) {
                $temp = $list[$i];
                $temp->{static::INDEX_KEY} = $new_index + $i + 2;
                $temp->save();
            }
        }

        DB::commit();
        static::ClearCache();
    }

    private static function _checkSlug($slug)
    {
        $check = static::where(static::SLUG_KEY,'like', $slug.'%')->count();
        return $check > 0 ? "-".$check : '';
    }

    private static function _getLatestIndex()
    {
        $index = static::max(static::INDEX_KEY);
        return is_null($index) ? 0 : $index;
    }

    // Cache
    public static function ClearCache()
    {
        foreach (static::CACHE_KEYS as $key) {
            Cache::forget($key);
        }
    }
}
