<?php

namespace App\Observers;

use App\Models\ModuleFeatureList;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Services\FileUploader;
use Bkwld\Croppa\Facades\Croppa;

class FeatureImageObserver
{
    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        Log::info("__construct");;
        $this->fileUploader = $fileUploader;
    }

    /**
     * Handle the ModuleFeatureList "created" event.
     */
    public function created(ModuleFeatureList $moduleFeatureList): void
    {
        dd('created',request()->all());
        if (request()->hasFile('name')) {
            $file = $this->fileUploader->handle(request()->file('name'));
            $moduleFeatureList->name = $file['filename'];
            $moduleFeatureList->fill(Arr::except($file, 'filename'));
        } else {
            if ($moduleFeatureList->type !== 'f') {
                // return false;
            }
        }
    }

    public function Creating(ModuleFeatureList $moduleFeatureList): void
    {
        dd('Creating',request()->all());
        if (request()->hasFile('name')) {
            $file = $this->fileUploader->handle(request()->file('name'));
            $moduleFeatureList->name = $file['filename'];
            $moduleFeatureList->fill(Arr::except($file, 'filename'));
        } else {
            if ($moduleFeatureList->type !== 'f') {
                // return false;
            }
        }
    }

    /**
     * Handle the ModuleFeatureList "updated" event.
     */
    public function Saving(ModuleFeatureList $moduleFeatureList): void
    {
        Log::info("Saving");;
        dd(request()->all());
        if (request()->hasFile('name')) {
            $file = $this->fileUploader->handle(request()->file('name'));
            $moduleFeatureList->name = $file['filename'];
            $moduleFeatureList->fill(Arr::except($file, 'filename'));
        } else {
            if ($moduleFeatureList->type !== 'f') {
                // return false;
            }
        }
    }

    public function Updating(ModuleFeatureList $moduleFeatureList): void
    {
        Log::info("Updating");;
        dd(request()->all());
        if (request()->hasFile('name')) {
            $file = $this->fileUploader->handle(request()->file('name'));
            $moduleFeatureList->name = $file['filename'];
            $moduleFeatureList->fill(Arr::except($file, 'filename'));
        } else {
            if ($moduleFeatureList->type !== 'f') {
                // return false;
            }
        }
    }

    /**
     * Handle the ModuleFeatureList "deleted" event.
     */
    public function deleted(ModuleFeatureList $moduleFeatureList): void
    {
        //
    }

    /**
     * Handle the ModuleFeatureList "restored" event.
     */
    public function restored(ModuleFeatureList $moduleFeatureList): void
    {
        //
    }

    /**
     * Handle the ModuleFeatureList "force deleted" event.
     */
    public function forceDeleted(ModuleFeatureList $moduleFeatureList): void
    {
        //
    }
}
