<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function destroy(Attachment $attachment)
    {
        $this->authorize('delete', $attachment);

        // Delete the file from storage
        Storage::delete($attachment->file_path);

        // Delete the attachment record
        $attachment->delete();

        return back()->with('success', 'Attachment deleted successfully.');
    }
}
