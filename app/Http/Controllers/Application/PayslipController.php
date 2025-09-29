<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class PayslipController extends Controller
{

    // =========================================
    // User Payslip Routes
    //

    /**
     *  User Payslip View route
     */
    public function user_payslip()
    {
        // List payslip files belonging to the authenticated user.
        $user = null;
        try { $user = auth('user')->user(); } catch (\Throwable $e) { $user = null; }
        $employeenumber = $user ? $user->employeenumber : null;

        $targetDir = storage_path('app/private/private/payslip');
        $files = [];
        if ($employeenumber && is_dir($targetDir)) {
            $all = scandir($targetDir);
            foreach ($all as $f) {
                if (in_array($f, ['.', '..'])) continue;
                $full = $targetDir . DIRECTORY_SEPARATOR . $f;
                if (!is_file($full)) continue;
                if (stripos($f, $employeenumber . '_') === 0 && strtolower(pathinfo($f, PATHINFO_EXTENSION)) === 'pdf') {
                    // Derive a user-friendly label from the filename.
                    $base = pathinfo($f, PATHINFO_FILENAME);
                    // Split at first underscore: employeenumber_datepart
                    $parts = explode('_', $base, 2);
                    $label = $f; // fallback
                    if (count($parts) === 2) {
                        $datePart = $parts[1];
                        // Normalize date part
                        $datePartClean = preg_replace('/[^0-9_\-:\.T]/', '', $datePart);
                        $dt = null;
                        // If purely 8 digits like 09102025 try common formats: MDY, DMY, YMD
                        if (preg_match('/^\d{8}$/', $datePartClean)) {
                            $tryFormats = ['mdY', 'dmY', 'Ymd'];
                            foreach ($tryFormats as $fmt) {
                                try {
                                    $dt = \Carbon\Carbon::createFromFormat($fmt, $datePartClean);
                                    if ($dt) break;
                                } catch (\Exception $e) {
                                    $dt = null;
                                }
                            }
                        } else {
                            try {
                                $dt = \Carbon\Carbon::parse($datePartClean);
                            } catch (\Exception $e) {
                                $dt = null;
                            }
                        }

                        if ($dt) {
                            // Long human-friendly date, e.g. "September 10, 2025"
                            $label = 'Payslip — ' . $dt->format('F j, Y');
                        } else {
                            // fallback: replace underscores/hyphens with spaces
                            $label = 'Payslip — ' . str_replace(['_', '-'], ' ', $datePart);
                        }
                    }

                    $files[] = [
                        'name' => $f,
                        'label' => $label,
                        'path' => $full,
                        'mtime' => filemtime($full),
                        'size' => filesize($full),
                    ];
                }
            }
            // Sort by newest first
            usort($files, function ($a, $b) { return $b['mtime'] <=> $a['mtime']; });
        }

        return view('user.apps.payslip.payslip', compact('files'));
    }

    /**
     * Download or view a payslip belonging to the authenticated user.
     */
    public function user_payslip_download(Request $request, $filename)
    {
        // Basic filename sanitization
        $basename = basename($filename);

        $user = null;
        try { $user = auth('user')->user(); } catch (\Throwable $e) { $user = null; }
        $employeenumber = $user ? $user->employeenumber : null;

        if (!$employeenumber) {
            abort(403, 'Unauthorized');
        }

        // Ensure the file belongs to this user by filename prefix
        if (stripos($basename, $employeenumber . '_') !== 0) {
            abort(403, 'Unauthorized');
        }

        $target = storage_path('app/private/private/payslip' . DIRECTORY_SEPARATOR . $basename);
        if (!file_exists($target) || !is_file($target)) {
            abort(404, 'File not found');
        }

        // If ?download=1 present, force download, otherwise display inline
        if ($request->query('download')) {
            return response()->download($target, $basename);
        }

        return response()->file($target, ['Content-Type' => 'application/pdf']);
    }

    //
    // =========================================


    // =========================================
    // Superadmin Payslip Routes
    //

    /**
     *  Superadmin Payslip View route
     */
    public function superadmin_payslip()
    {
        return view('superadmin.apps.payslip.payslip');
    }

    /**
     *  Superadmin Payslip Upload route
     */
    public function superadmin_payslip_upload(Request $request)
    {
        // Validate uploaded files: require either payslip_files or payslip_zip
        $request->validate([
            'payslip_files' => 'required_without:payslip_zip',
            'payslip_files.*' => 'file|mimes:pdf|max:20480',
            'payslip_zip' => 'nullable|file|mimes:zip|max:51200', // ZIP up to 50MB
        ]);

        $stored = [];
        $errors = [];

        // Ensure target directory exists
        $targetDir = storage_path('app/private/private/payslip');
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
                Log::error('Failed to create payslip storage directory: ' . $targetDir);
                return redirect()->back()->with('error', 'Server error: unable to prepare storage directory.');
            }
        }

        // Handle direct PDF uploads (multiple)
        $files = $request->file('payslip_files');
        if ($files && count($files) > 0) {
            foreach ($files as $file) {
                if (!$file || !$file->isValid()) {
                    $errors[] = 'One of the uploaded PDF files was invalid.';
                    continue;
                }

                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $safeName = Str::slug(substr($originalName, 0, 50));
                $filename = time() . '_' . $safeName . '_' . Str::random(6) . '.' . $ext;

                try {
                    $file->move($targetDir, $filename);
                    $fullPath = $targetDir . DIRECTORY_SEPARATOR . $filename;
                    if (file_exists($fullPath)) {
                        $stored[] = 'private/private/payslip/' . $filename;
                    } else {
                        Log::error('Failed to move payslip file to target directory: ' . $fullPath);
                        $errors[] = 'Failed to store: ' . $file->getClientOriginalName();
                    }
                } catch (\Exception $e) {
                    Log::error('Exception storing payslip file: ' . $e->getMessage());
                    $errors[] = 'Exception storing: ' . $file->getClientOriginalName();
                }
            }
        }

        // Handle single ZIP upload containing PDFs
        $zipFile = $request->file('payslip_zip');
        if ($zipFile && $zipFile->isValid()) {
            $tmpZipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'upload_' . uniqid() . '.zip';
            try {
                $zipFile->move(dirname($tmpZipPath), basename($tmpZipPath));
            } catch (\Exception $e) {
                Log::error('Failed to move uploaded zip to temp: ' . $e->getMessage());
                $errors[] = 'Failed to process uploaded ZIP.';
                $tmpZipPath = null;
            }

            if (!empty($tmpZipPath) && file_exists($tmpZipPath)) {
                $zip = new ZipArchive();
                if ($zip->open($tmpZipPath) === true) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $stat = $zip->statIndex($i);
                        if (!$stat || empty($stat['name'])) continue;

                        $entryName = $stat['name'];
                        // skip directories and __MACOSX
                        if (substr($entryName, -1) === '/' || strpos($entryName, '__MACOSX') === 0) continue;

                        $baseName = basename($entryName);
                        if (strtolower(pathinfo($baseName, PATHINFO_EXTENSION)) !== 'pdf') {
                            // skip non-pdf files
                            continue;
                        }

                        $stream = $zip->getStream($entryName);
                        if (!$stream) {
                            $errors[] = 'Unable to extract file from ZIP: ' . $entryName;
                            continue;
                        }

                        $tmpPdf = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'payslip_' . uniqid() . '.pdf';
                        $out = fopen($tmpPdf, 'w');
                        while (!feof($stream)) {
                            fwrite($out, fread($stream, 1024));
                        }
                        fclose($out);
                        fclose($stream);

                        // Basic validation: file size and mime
                        $filesizeKb = filesize($tmpPdf) / 1024;
                        $finfo = new \finfo(FILEINFO_MIME_TYPE);
                        $mime = $finfo->file($tmpPdf);
                        if ($mime !== 'application/pdf') {
                            @unlink($tmpPdf);
                            $errors[] = 'Extracted file is not a valid PDF: ' . $entryName;
                            continue;
                        }
                        if ($filesizeKb > 20480) { // >20MB
                            @unlink($tmpPdf);
                            $errors[] = 'Extracted file too large (>20MB): ' . $entryName;
                            continue;
                        }

                        // Generate safe filename and move to target
                        $originalName = pathinfo($baseName, PATHINFO_FILENAME);
                        $safeName = Str::slug(substr($originalName, 0, 50));
                        $filename = time() . '_' . $safeName . '_' . Str::random(6) . '.pdf';
                        $destPath = $targetDir . DIRECTORY_SEPARATOR . $filename;
                        if (rename($tmpPdf, $destPath)) {
                            $stored[] = 'private/private/payslip/' . $filename;
                        } else {
                            @unlink($tmpPdf);
                            $errors[] = 'Failed to store extracted PDF: ' . $entryName;
                        }
                    }
                    $zip->close();
                } else {
                    Log::error('Unable to open uploaded ZIP file: ' . $tmpZipPath);
                    $errors[] = 'Uploaded ZIP could not be opened.';
                }

                // remove temporary zip
                @unlink($tmpZipPath);
            }
        }

        if (count($stored) === 0) {
            if (count($errors) === 0) {
                return redirect()->back()->with('error', 'No valid files were uploaded.');
            }
            return redirect()->back()->with('error', implode('\n', $errors));
        }

        // Attempt to get user id safely for logs
        $userId = null;
        try {
            $userId = auth() ? auth()->id() : null;
        } catch (\Throwable $e) {
            $userId = null;
        }
        Log::info('Payslip files uploaded', ['paths' => $stored, 'user_id' => $userId]);

        return redirect()->back()->with('success', 'Successfully uploaded ' . count($stored) . ' file(s). Stored: ' . implode(', ', array_map(function ($p) {
            return basename($p);
        }, $stored)));
    }
    // =========================================
}
